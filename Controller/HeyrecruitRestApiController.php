<?php

namespace heyrecruit\Controller;

defined('ABSPATH') || exit;

use Exception;
use InvalidArgumentException;

class HeyrecruitRestApiController {

    /**
     * Auth configuration.
     * array    ['client_id']       int The company id of a registered SCOPE client.
     *          ['client_secret']   string The company secret.
     * @var array $auth_config (See above)
     */
    private array $auth_config = [
        'client_id'     => null,
        'client_secret' => null
    ];

    /**
     * Job filter data submitted with get jobs request.
     *
     * array['job_ids']                 array job ids
     *      ['company_location_ids']    array company location ids
     *      ['department']              array Job department (e.g. Software development)
     *      ['employment']              array Job employment (e.g. full time, part time)
     *      ['language']                string The language shortcut for strings to be returned from scope
     *      ['address']                 string Job address
     *      ['search']                  string search
     *      ['area_search_distance']    int Area search distance for address. Default 60000 => 60 km
     *      ['internal_title']          string Internal job title
     *
     *
     * @var array $filter (See above)
     *
     */
    protected array $filter = [
        'departments'          => [],
        'employments'          => [],
        'company_location_ids' => [],
        'language'             => 'de',
        'address'              => null,
        'internal_title'       => null,
        'page'                 => 1
    ];

    /**
     * SCOPE API request urls.
     *
     * array['auth']                string Authentication and requesting an authorization token url.
     *      ['get_company']         string Get company data url.
     *      ['get_jobs']            string Get jobs data url.
     *      ['get_job']             string Get single job data url.
     *      ['apply']       string Add  new applicant url.
     *      ['upload_documents']    string Upload applicant documents url.
     *      ['delete_documents']    string Delete applicant documents url.
     *
     *
     * @var string[] $path (See above)
     */
    private array $path = [
        'auth'             => 'auth',
        'get_company'      => 'companies/view',
        'get_jobs'         => 'jobs/index',
        'get_job'          => 'jobs/view',
        'apply'            => 'applicant-jobs/apply',
        'upload_documents' => 'rest-applicants/uploadDocument',
        'delete_documents' => 'rest-applicants/deleteDocument',
    ];

    /**
     * @var array|int[]
     */
    protected array $employments = [
        'full-time'                        => 1,
        'vollzeit'                         => 1,
        'internship'                       => 2,
        'praktikum'                        => 2,
        'freelancer'                       => 3,
        'part-time'                        => 4,
        'teilzeit'                         => 4,
        'project work'                     => 5,
        'projektarbeit'                    => 5,
        'apprenticeship'                   => 6,
        'ausbildung'                       => 6,
        'working student'                  => 7,
        'werkstudent'                      => 7,
        'temporary'                        => 8,
        'aushilfe'                         => 8,
        'temporary employment (anü)'       => 9,
        'temporary employment agency work' => 9,
        'anü'                              => 9
    ];

    /**
     * @var string
     */
    protected string $addApplicantPath;

    /**
     * __construct
     */
    function __construct() {

        if (!defined('HEYRECRUIT_URL')) {
            throw new InvalidArgumentException('Missing HEYRECRUIT_URL parameter.');
        }

        $this->addApplicantPath = $this->path['apply'];

        $this->setAuthConfig();
        $this->authenticate();
    }

    /**
     *  Sets auth data for requesting an JWT access token
     *  ['SCOPE_CLIENT_SECRET']     string The client secret of a registered SCOPE client.
     * @return void
     */
    private function setAuthConfig(): void {

        if (!defined('HEYRECRUIT_COMPANY_ID')) {
            throw new InvalidArgumentException('Missing HEYRECRUIT_COMPANY_ID parameter.');
        }
        if (!defined('HEYRECRUIT_API_SECRET_KEY')) {
            throw new InvalidArgumentException('Missing SCOPE_CLIENT_SECRET parameter.');
        }

        $this->auth_config = [
            'client_id'     => HEYRECRUIT_COMPANY_ID,
            'client_secret' => HEYRECRUIT_API_SECRET_KEY
        ];
    }

    /**
     *  Swaps a client_id and client_secret for an JWT auth token.
     *
     * @return bool
     */
    private function authenticate(): bool {

        if ($this->checkTokenForValidity())
            return true;

        $result = $this->curlPost($this->path['auth'], $this->auth_config);

        if (($result['status'] ?? false) === 'success'
            && isset($result['data']['token'])
            && isset($result['data']['expiration'])) {

            $_SESSION['HEYRECRUIT_TOKEN'] = esc_attr($result['data']['token']);
            $_SESSION['HEYRECRUIT_TOKEN_EXPIRY_TIME'] = (int)esc_attr($result['data']['expiration']);

            return true;
        }

        return false;
    }

    /**
     * checkTokenForValidity
     * @return bool
     */
    private function checkTokenForValidity(): bool {

        if (
            (
                isset($_SESSION['HEYRECRUIT_COMPANY']['id'])
                && $_SESSION['HEYRECRUIT_COMPANY']['id'] !== (int)HEYRECRUIT_COMPANY_ID
            )
            ||
            (
                HEYRECRUIT_COMPANY_ID && HEYRECRUIT_API_SECRET_KEY
                && ($_SESSION['HEYRECRUIT_TOKEN_EXPIRY_TIME'] ?? 0) < time()
            )

        ) {

            $this->unsetHeyrecruitApiSessions();

            return false;
        }

        return true;
    }

    /**
     * unsetSessions
     *
     * @return void
     */
    protected function unsetHeyrecruitApiSessions() {

        if (isset($_SESSION['HEYRECRUIT_TOKEN']))
            unset($_SESSION['HEYRECRUIT_TOKEN']);

        if (isset($_SESSION['HEYRECRUIT_TOKEN_EXPIRY_TIME']))
            unset($_SESSION['HEYRECRUIT_TOKEN_EXPIRY_TIME']);

        if (isset($_SESSION['HEYRECRUIT_COMPANY']))
            unset($_SESSION['HEYRECRUIT_COMPANY']);

        if (isset($_SESSION['HEYRECRUIT_JOBS']))
            unset($_SESSION['HEYRECRUIT_JOBS']);

    }

    /**
     * checkAuthenticationData
     * @return bool
     * @throws Exception
     */
    protected function checkAuthenticationData(): bool {

        return $this->authenticate();
    }

    /**
     *  Get company data.
     * @return array
     * @throws Exception
     */
    public function getCompany(): array {

        $result = $this->curlGet($this->path['get_company'] . '/');

        return $_SESSION['HEYRECRUIT_COMPANY'] = $result['data'] ?? [];
    }

    /**
     *  Find jobs from SCOPE based on the pre defined filter values.
     * @return array
     * @throws Exception
     */
    public function getHrJobs(): array {

        $result = $this->curlGet($this->path['get_jobs'], $this->filter);

        return [
            'jobs'                 => $result['data']['jobs'] ?? [],
            'employment_type_list' => $result['data']['employment_type_list'] ?? [],
            'department_list'      => $result['data']['department_list'] ?? [],
            'pagination'           => $result['data']['pagination'] ?? []
        ];
    }

    /**
     *  Get one job from SCOPE.
     *
     * @param int $jobId : The ID of the job to get from SCOPE.
     * @param int $locationId : The ID of the company location that belongs to the job.
     *
     * @return array
     * @throws Exception
     */
    public function getHrJob(int $jobId, int $locationId): array {

        if (isset($_SESSION['HEYRECRUIT_JOBS']['job_' . $jobId . '_' . $locationId])
            && !empty($_SESSION['HEYRECRUIT_JOBS']['job_' . $jobId . '_' . $locationId])
        )
            return $_SESSION['HEYRECRUIT_JOBS']['job_' . $jobId . '_' . $locationId];

        if (empty($jobId)) {
            throw new Exception('Missing job id parameter');
        }
        if (empty($locationId)) {
            throw new Exception('Missing company location id parameter');
        }

        $url = $this->path['get_job'] . '?job_id=' . $jobId . '&company_location_id=' . $locationId;

        $result = $this->curlGet($url);

        $data = $result['data'] ?? [];

        $_SESSION['HEYRECRUIT_JOBS']['job_' . $jobId . '_' . $locationId] = $data;

        return $data;
    }

    /**
     * addApplicant
     *
     * @param int $jobId
     * @param int $locationId
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function addApplicant(int $jobId, int $locationId, array $data): array {

        $url = $this->addApplicantPath . '?job_id=' . $jobId . '&company_location_id=' . $locationId;

        return $this->curlPost($url, $data);
    }

    /**
     * removeUtf8Bom
     *
     * @param $text
     *
     * @description  Remove multiple UTF-8 BOM sequences
     * @return string
     */
    private function removeUtf8Bom($text): string {
        $bom = pack('H*', 'EFBBBF');

        return preg_replace("/^$bom/", '', $text);
    }

    /**
     * curlGet
     *
     * @param string $url
     * @param array|null $query
     *
     * @return array
     */
    private function curlGet(string $url, ?array $query = []): array {

        $queryString = http_build_query($query);

        $query = strpos($url, '?') !== false ? '&' . $queryString : '?' . $queryString;

        return $this->sendCurl($url . $query, [], 'GET') ?? [];

    }

    /**
     * curlPost
     *
     * @param string $path
     * @param array $data
     *
     * @return array
     */
    private function curlPost(string $path, array $data = []): array {

        return $this->sendCurl($path, $data);
    }

    /**
     * sendCurl
     *
     * @param string $path
     * @param array $data
     * @param string $sendType
     *
     * @return array
     */
    private function sendCurl(string $path, array $data, string $sendType = 'POST'): array {

        $dataJson = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => HEYRECRUIT_URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $sendType,
            CURLOPT_POSTFIELDS     => $dataJson,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'charset: UTF-8',
                'Content-Length: ' . strlen($dataJson),
                'Authorization: Bearer ' .
                esc_attr(($_SESSION['HEYRECRUIT_TOKEN'] ?? null))
            ],
        ]);

        $response = json_decode($this->removeUtf8Bom(curl_exec($curl)), true);

        curl_close($curl);

        return $response;
    }

    /**
     * getGoogleTagCode
     * @return object
     * @throws Exception
     */
    protected function getGoogleTagCode(): object {

        $company = $this->getCompany();

        $publicId = $company['CompanySetting']['google_tag_public_id'] ?? null;

        $tagCode = [
            'head' => '',
            'body' => ''
        ];

        if (!empty($publicId)) {
            $tagCode['head'] =
                "<!-- Google Tag Manager -->" .
                "<script> (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': " .
                "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], " .
                "j=d.createElement(s),dl=l!=='dataLayer'?'&l='+l:'';j.async=true;j.src= " .
                "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); " .
                "})(window,document,'script','dataLayer', '" .
                $publicId . "');</script> " .
                "<!-- End Google Tag Manager -->";

            $tagCode['body'] =
                '<!-- Google Tag Manager (noscript) -->' .
                '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $publicId . '" ' .
                'height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> ' .
                '<!-- End Google Tag Manager (noscript) -->';
        }

        return (object)$tagCode;
    }
}