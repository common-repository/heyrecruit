<?php

namespace heyrecruit\Controller;

use Exception;

defined('ABSPATH') || exit;


class HeyrecruitJobDetailController extends HeyrecruitMainController {

    /**
     * @var array
     */
    private array $job;

    /**
     * @var array
     */
    private array $element;

    /**
     * @var object|null
     */
    private ?object $formElement;

    /**
     * @var string
     */
    private string $fallbackConfirmSlug;

    /**
     * __construct
     * @throws Exception
     */
    public function __construct() {

        parent::__construct();

        add_shortcode('hr_job_header', [
            $this,
            'displayJobHeader'
        ]);

        add_shortcode('hr_job_header_without_image', [
            $this,
            'displayJobHeaderWithoutImage'
        ]);

        add_shortcode('hr_job_title', [
            $this,
            'displayJobTitle'
        ]);

        add_shortcode('hr_job_sub_title', [
            $this,
            'displayJobSubTitle'
        ]);

        add_shortcode('hr_job_internal_title', [
            $this,
            'displayInternalTitle'
        ]);

        add_shortcode('hr_job_employment', [
            $this,
            'displayJobEmployment'
        ]);

        add_shortcode('hr_job_department', [
            $this,
            'displayJobDepartment'
        ]);

        add_shortcode('hr_job_allow_home_office', [
            $this,
            'displayJobAllowHomeOffice'
        ]);

        add_shortcode('hr_job_min_salary', [
            $this,
            'displayJobMinSalary'
        ]);

        add_shortcode('hr_job_max_salary', [
            $this,
            'displayJobMaxSalary'
        ]);

        add_shortcode('hr_job_description', [
            $this,
            'displayJobDescription'
        ]);

        add_shortcode('hr_job_details', [
            $this,
            'displayJobDetails'
        ]);

        add_shortcode('hr_job_sections', [
            $this,
            'displayJobSections'
        ]);

        add_shortcode('hr_select_location', [
            $this,
            'displaySelectLocation'
        ]);

        add_shortcode('hr_job_form', [
            $this,
            'displayJobForm'
        ]);

        add_shortcode('hr_job_street', [
            $this,
            'displayJobStreet'
        ]);

        add_shortcode('hr_job_house_number', [
            $this,
            'displayJobHouseNumber'
        ]);

        add_shortcode('hr_job_postal_code', [
            $this,
            'displayJobPostalCode'
        ]);

        add_shortcode('hr_job_city', [
            $this,
            'displayJobCity'
        ]);

        add_shortcode('hr_job_state', [
            $this,
            'displayJobState'
        ]);

        add_shortcode('hr_job_country', [
            $this,
            'displayJobCountry'
        ]);

        add_shortcode('hr_job_address', [
            $this,
            'displayJobAddress'
        ]);

        #### actions
        add_action('wp_ajax_hr_send_applicant', [
            $this,
            'sendApplicant'
        ], 20);

        add_action('wp_ajax_nopriv_hr_send_applicant', [
            $this,
            'sendApplicant'
        ], 10);
    }

    /**
     * displayJobHeader
     * @return string|null
     * @throws Exception
     */
    public function displayJobHeader(): ?string {

        if (!$this->getJobData())
            return '';

        foreach ($this->job['job_sections'] ?? [] as $section) {

            if ($section['section_type'] !== 'header')
                continue;

            foreach ($section['job_section_elements'] as $element) {

                if ($element['element_type'] === 'image') {

                    $jobHeaderImage = json_decode($element['job_section_element_strings'][0]['text'])[0] ?? false;

                    if ($jobHeaderImage)
                        break 2;
                }
            }
        }

        $args = [
            'jobHeaderImage'   => esc_attr($jobHeaderImage ?? null),
            'jobTitle'         => $this->displayJobTitle(),
            'jobSubTitle'      => $this->displayJobSubTitle(),
            'jobDescription'   => $this->displayJobDescription()
        ];

        return $this->loadTemplateForShortcode('header',
            $args, 'sections', false);
    }


    /**
     * displayJobHeaderWithoutImage
     * @return string|null
     * @throws Exception
     */
    public function displayJobHeaderWithoutImage(): ?string {

        if (!$this->getJobData())
            return '';

        foreach ($this->job['job_sections'] ?? [] as $section) {

            if ($section['section_type'] !== 'header')
                continue;

        }

        $args = [
            'jobTitle'         => $this->displayJobTitle(),
            'jobSubTitle'      => $this->displayJobSubTitle(),
            'jobDescription'   => $this->displayJobDescription()
        ];

        return $this->loadTemplateForShortcode('header_without_image',
            $args, 'sections', false);
    }

    /**
     * displayJobTitle
     * @return string
     * @throws Exception
     */
    public function displayJobTitle(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['title'])) {

            $args = ['jobTitle' => esc_attr($this->job['job_strings'][0]['title'])];

            return $this->loadTemplateForShortcode('job_title',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayInternalTitle
     * @return string
     * @throws Exception
     */
    public function displayInternalTitle(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['internal_title'])) {

            $args = ['jobInternalTitle' => esc_attr($this->job['internal_title'])];

            return $this->loadTemplateForShortcode('job_internal_title',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayJobTitle
     * @return string
     * @throws Exception
     */
    public function displayJobSubTitle(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['subtitle'])) {

            $args = ['jobTitle' => esc_attr($this->job['job_strings'][0]['subtitle'])];

            return $this->loadTemplateForShortcode('job_sub_title',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayJobEmployment
     * @return string
     * @throws Exception
     */
    public function displayJobEmployment(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['employment'])) {

            $args = ['jobEmployment' => esc_attr($this->job['job_strings'][0]['employment'])];

            return $this->loadTemplateForShortcode('job_employment',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayJobDepartment
     * @return string
     * @throws Exception
     */
    public function displayJobDepartment(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['department'])) {

            $args = ['jobDepartment' => esc_attr($this->job['job_strings'][0]['department'])];

            return $this->loadTemplateForShortcode('job_department',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayJobDepartment
     * @return string
     * @throws Exception
     */
    public function displayJobAllowHomeOffice(): string {

        if (!$this->getJobData())
            return '';

        switch ($this->job['remote'] ?? null) {
            case 'complete':
                $jobHomeoffice = __('Yes');
                break;
            case 'sometimes':
                $jobHomeoffice = __('Partly', HEYRECRUIT_OPTION_KEY_NAME);
                break;
            default:
                $jobHomeoffice = __('No');
        }

        return $this->loadTemplateForShortcode('job_homeoffice',
            compact('jobHomeoffice'), 'sections', false);
    }

    /**
     * displayJobMinSalary
     * @return string
     * @throws Exception
     */
    public function displayJobMinSalary(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['salary_min'])) {

            return $this->loadTemplateForShortcode('job_salary', [
                'jobSalary' => esc_attr($this->job['job_strings'][0]['salary_min'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobMaxSalary
     * @return string
     * @throws Exception
     */
    public function displayJobMaxSalary(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['salary_max'])) {

            return $this->loadTemplateForShortcode('job_salary', [
                'jobSalary' => esc_attr($this->job['job_strings'][0]['salary_max'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobSubDescription
     * @return string
     * @throws Exception
     */
    public function displayJobDescription(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['job_strings'][0]['description'])) {

            $args = ['jobDescription' => wp_kses_post($this->job['job_strings'][0]['description'])];

            return $this->loadTemplateForShortcode('job_description',
                $args, 'sections', false);
        }

        return '';
    }

    /**
     * displayJobSections
     * @return string|null
     * @throws Exception
     */
    public function displayJobSections(): ?string {

        if (!$this->getJobData())
            return '';

        $args = [
            'sectionType' => '',
            'sections'    => []
        ];

        foreach ($this->job['job_sections'] ?? [] as $section) {

            if ($section['section_type'] === 'header' || $section['section_type'] === 'company_info') {
                continue;
            }

            $args['sectionType'] = $section['section_type'];

            foreach ($section['job_section_elements'] as $element) {

                if ($element['element_type'] === 'form') {
                    continue;
                }

                $this->element = $element;

                $section = $this->loadSection();

                if (!empty($section))
                    $args['sections'][] = $section;
            }
        }

        return $this->loadTemplateForShortcode('job_sections',
            $args, 'sections', false);
    }

    /**
     * displaySelectLocation
     * @return  string
     * @throws Exception
     */
    public function displaySelectLocation(): string {

        if (!$this->getJobData())
            return '';

        $locations = $this->getLocationsForSelectOptions($this->job['active_company_location_jobs']);

        if (count($locations) > 1) {

            $queryJobLocationId = (int)sanitize_text_field($_GET['locationId']);

            foreach ($locations as $key => $location) {

                $optionSelected = $key === $queryJobLocationId ? ' selected' : '';

                $optionArgs = [
                    'optionName'     => esc_attr($location),
                    'optionValue'    => esc_attr($key),
                    'optionSelected' => $optionSelected
                ];

                $args['selectLocationOptions'][] = $this->loadTemplateForShortcode(
                    'select_location_option',
                    $optionArgs,
                    'formElements',
                    false
                );

            }

            return $this->loadTemplateForShortcode(
                'select_location',
                $args ?? [],
                'formElements'
            );
        }

        return '';
    }

    /**
     * displayForm
     * @return string
     * @throws Exception
     */
    public function displayJobForm(): string {

        $this->fallbackConfirmSlug = __('successfully-applied', HEYRECRUIT_OPTION_KEY_NAME);

        if (isset($_GET[$this->fallbackConfirmSlug]) && sanitize_text_field($_GET[$this->fallbackConfirmSlug]) == 1) {
            return $this->loadTemplateForShortcode('fallback-confirm');
        }

        if (!$this->getJobData())
            return '';

        $args['companyId'] = $this->getOption('companyId');
        $args['jobId'] = $this->job['id'];


        $args['jobLocationId'] = isset($_GET['locationId'])
            ? (int)sanitize_text_field($_GET['locationId'])
            : $this->job['company_location_jobs'][0]['company_location_id'];


        $args['postUrl'] = HEYRECRUIT_URL . $this->addApplicantPath;

        $args['confirmPageUrl'] = $this->getConfirmPageUrl();

        foreach ($this->job['job_sections'] as $section) {
            foreach ($section['job_section_elements'] as $formElement) {

                if ($formElement['element_type'] !== 'form')
                    continue;

                $elements = json_decode($formElement['job_section_element_strings'][0]['text']) ?? [];

                foreach ($elements as $key => $element) {

                    $this->formElement = $element;

                    $formElementTitle = $this->loadFormSectionTitle();
                    $formSectionElement = $this->loadFormSectionElements();

                    if (!empty($formSectionElement))
                        $args['formSections'][] = [
                            'formSectionTitle'    => $formElementTitle,
                            'formSectionElements' => $formSectionElement
                        ];

                }
            }
        }

        return $this->loadTemplateForShortcode('form', $args, 'formElements');
    }

    /**
     * loadFormSectionElement
     * @return array
     */
    private function loadFormSectionElements(): array {

        $formElements = $this->formElement ?? [];

        foreach ($formElements->questions as $formElement) {

            $args['id'] = esc_attr(($formElement->question_strings[0]->question_id ?? null));
            $args['fieldName'] = esc_attr(($formElement->field_name ?? null));
            $args['required'] = $formElement->required ? ' required' : null;
            $args['requiredMark'] = $formElement->required ? ' *' : null;

            $args['title'] = esc_attr(($formElement->question_strings[0]->title ?? null));
            $args['placeholder'] = esc_attr(($formElement->question_strings[0]->placeholder ?? null));


            if ($formElement->form_type === 'checkbox_with_modal') {

                $modal = json_decode($formElement->question_strings[0]->modal_value);

                if (isset($modal->href) && isset($modal->value)) {
                    $modalLink = '<a class="openModal primary-color"
										href="#modal' . $args['id'] . '"
										data-id="' . $args['id'] . '">' . esc_attr($modal->href) . '</a>';


                    $elementValue = str_replace($modal->href, $modalLink, $formElement->question_strings[0]->value);
                    $args['value'] = wp_kses_post($elementValue);
                    $args['modalBody'] = wp_kses_post($modal->value);

                } else {
                    $elementValue = $formElement->question_strings[0]->value;
                    $args['value'] = wp_kses_post($elementValue);
                }


            } else {

                $stringValue = ($formElement->question_strings[0]->value
                    ?? ($formElement->default_value ?? ''));


                $args['value'] = $this->containsSubstring($stringValue, ';')
                    ? explode(";", esc_attr($stringValue))
                    : explode(",", esc_attr($stringValue));
            }

            $newFormElements[] = $this->loadTemplateForShortcode(
                $formElement->form_type,
                $args,
                'formElements', false
            );
        }

        return $newFormElements ?? [];
    }

    /**
     * displayJobDetails
     * @return string|null
     * @throws Exception
     */
    public function displayJobDetails(): ?string {

        if (!$this->getJobData())
            return '';

        $args = [
            'jobAddress'         => $this->displayJobAddress(),
            'jobEmployment'      => $this->displayJobEmployment(),
            'jobDepartment'      => $this->displayJobDepartment(),
            'jobAllowHomeOffice' => $this->displayJobAllowHomeOffice(),
            'jobMinSalary'       => $this->displayJobMinSalary(),
            'jobMaxSalary'       => $this->displayJobMaxSalary()
        ];

        return $this->loadTemplateForShortcode('job_details',
            $args, 'sections', false);
    }

    /**
     * displayJobStreet
     * @return string
     * @throws Exception
     */
    public function displayJobStreet(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['company_location_jobs'][0]['company_location']['street'])) {

            return $this->loadTemplateForShortcode('job_street', [
                'jobStreet' => esc_attr($this->job['company_location_jobs'][0]['company_location']['street'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobHouseNumber
     * @return string
     * @throws Exception
     */
    public function displayJobHouseNumber(): string {

        if (!$this->getJobData())
            return '';


        if (!empty($this->job['company_location_jobs'][0]['company_location']['street'])) {

            return $this->loadTemplateForShortcode('job_street_number', [
                'jobStreetNumber' => esc_attr($this->job['company_location_jobs'][0]['company_location']['street_number'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobPostalCode
     * @return string
     * @throws Exception
     */
    public function displayJobPostalCode(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['company_location_jobs'][0]['company_location']['postal_code'])) {

            return $this->loadTemplateForShortcode('job_postal_code', [
                'jobPostalCode' => esc_attr($this->job['company_location_jobs'][0]['company_location']['postal_code'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobCity
     * @return string
     * @throws Exception
     */
    public function displayJobCity(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['company_location_jobs'][0]['company_location']['city'])) {

            return $this->loadTemplateForShortcode('job_city', [
                'jobCity' => esc_attr($this->job['company_location_jobs'][0]['company_location']['city'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobState
     * @return string
     * @throws Exception
     */
    public function displayJobState(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['company_location_jobs'][0]['company_location']['state'])) {

            return $this->loadTemplateForShortcode('job_state', [
                'jobState' => esc_attr($this->job['company_location_jobs'][0]['company_location']['state'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobCountry
     * @return string
     * @throws Exception
     */
    public function displayJobCountry(): string {

        if (!$this->getJobData())
            return '';

        if (!empty($this->job['company_location_jobs'][0]['company_location']['country'])) {

            return $this->loadTemplateForShortcode('job_country', [
                'jobCountry' => esc_attr($this->job['company_location_jobs'][0]['company_location']['country'])
            ], 'sections', false);
        }

        return '';
    }

    /**
     * displayJobAddress
     * @return string
     * @throws Exception
     */
    public function displayJobAddress(): string {

        if (!$this->getJobData())
            return '';

        $locationId = (int)sanitize_text_field($_GET['locationId']);

        foreach (($this->job['active_company_location_jobs'] ?? []) as $locationKey => $LocationItem) {

            if ($LocationItem['company_location_id'] === $locationId) {

                $jobLocation = $this->jobLocation($this->job['active_company_location_jobs'], $locationKey);

                break;
            }
        }

        $jobAddress = !empty($jobLocation['jobLocationTitle'])
            ? esc_attr(trim($jobLocation['jobLocationTitle']))
            : $this->getFormattedAddress(($this->job['active_company_location_jobs'][0]) ?? []);

        if (!empty($jobAddress))
            return $this->loadTemplateForShortcode('job_address',
                compact('jobAddress'), 'sections', false);

        return '';
    }

    /**
     * loadSection
     * @return string|null
     */
    private function loadSection(): ?string {

        $elementType = $this->element['element_type'] ?? false;

        $textElement = $this->element['job_section_element_strings'][0]['text'] ?? false;
        $htmlElement = $this->element['job_section_element_strings'][0]['html'] ?? false;


        if ($elementType && ($textElement || $htmlElement)) {

            $args = [
                'text' => false,
                'html' => false,
            ];

            if ($elementType === 'image') {

                $jsonData = json_decode($textElement);

                if ($jsonData && isset($jsonData[0]))
                    $args['imagesSrc'] = esc_attr($jsonData[0]);

            } else {

                $args = [
                    'text' => !$textElement ? null : wp_kses_post($textElement),
                    'html' => !$htmlElement ? null : wp_kses_post($htmlElement)
                ];
            }

            return $this->loadTemplateForShortcode($elementType, $args, 'sections', false);
        }

        return null;
    }

    /**
     * loadFormSectionTitle
     * @return string|null
     */
    private function loadFormSectionTitle(): ?string {

        $sectionTitle = $this->formElement->question_category_strings[0]->title ?? false;

        if ($sectionTitle) {

            return $this->loadTemplateForShortcode('form_section_title', [
                'formSectionTitle' => esc_attr($sectionTitle)
            ], 'sections', false);
        }

        return null;
    }

    /**
     * getLocationsForSelectOptions
     * @param array $locations
     * @return array
     */
    private function getLocationsForSelectOptions(array $locations): array {

        foreach ($locations as $location) {

            $locationId = $location["company_location"]['id'] ?? false;

            if ($locationId)
                $locationData[$locationId] = $this->getFormattedAddress($location);
        }

        return $locationData ?? [];
    }

    /**
     * getJobData
     * @return bool
     * @throws Exception
     */
    private function getJobData(): bool {

        if (isset($_GET['jobId']) && isset($_GET['locationId'])) {
            $this->job = $this->getHrJob(
                (int)sanitize_text_field($_GET['jobId']),
                (int)sanitize_text_field($_GET['locationId'])
            );
        }

        return isset($this->job) && count($this->job) > 0;
    }

    /**
     * getConfirmPageUrl
     * @return string
     */
    private function getConfirmPageUrl(): string {

        $externalConfirmUrl = $this->getOption('confirmPage', false);
        $confirmPageId = $this->getOption('ConfirmPageId', false);

        if ($externalConfirmUrl)
            $confirmPage = $externalConfirmUrl;
        elseif ($confirmPageId && get_permalink($confirmPageId)) {
            $confirmPage = get_permalink($confirmPageId);
        } else
            $confirmPage = add_query_arg([
                $this->fallbackConfirmSlug => 1
            ], get_permalink($this->getOption('jobDetailPageId')));

        return esc_url($confirmPage);
    }

    /**
     * sendApplicant
     *
     * @return void
     * @throws Exception
     */
    public function sendApplicant(): void {

        if (isset($_POST['data'])) {
            $applicantData = $_POST['data'];

            $jobId = (int)sanitize_text_field($applicantData['jobId']);
            $locationId = (int)sanitize_text_field($applicantData['locationId']);

            if (!empty($applicantData['files']))

                foreach ($applicantData['files'] as &$file)
                    if (!empty($file['data'])) {
                        $files[] = [
                            'name'         => sanitize_text_field($file['name']),
                            'type'         => sanitize_text_field($file['type']),
                            'question_id'  => sanitize_text_field($file['question_id']),
                            'content_type' => sanitize_text_field($file['content_type']),
                            'data'         => sanitize_text_field($file['data']),
                        ];
                    }

            if (sanitize_text_field($applicantData['consent_form_accepted']) == 1)
                $applicantData['consent_form_accepted'] = 'yes';

            unset(
                $applicantData['jobId'],
                $applicantData['locationId'],
                $applicantData['files'],
                $applicantData['document_type']
            );

            $data = array_merge(
                array_map('sanitize_textarea_field', $applicantData),
                [
                    'files' => ($files ?? [])
                ],
                [
                    'analytics' => [
                        'application' => 'WordPress',
                        'referrer'    => isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : null
                    ]
                ]
            );
        }

        wp_send_json(
            $this->addApplicant(
                $jobId ?? 0,
                $locationId ?? 0,
                ['applicant' => ($data ?? null)]
            ), 200);
    }

}