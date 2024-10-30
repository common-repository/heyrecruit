<?php

namespace heyrecruit\Controller;

defined('ABSPATH') || exit;

use Exception;
use stdClass;

class HeyrecruitJobsOverviewController extends HeyrecruitMainController {

    /**
     * @var array
     */
    private array $jobs;

    /**
     * @var array
     */
    private array $company;

    /**
     * @var string
     */
    private string $currentShortcode = 'hr_jobs_list';

    /**
     * __construct
     * @throws Exception
     */
    public function __construct() {

        parent::__construct();

        add_shortcode('hr_jobs_map', function ($args) {

            $this->setFilter($args);

            return $this->displayGoogleMap();
        });

        add_shortcode('hr_filter_options', function ($args) {

            $this->setFilter($args);

            return $this->displayFilterOptions();
        });

        add_shortcode('hr_jobs_list', function ($args) {

            $this->setFilter($args);

            return $this->displayJobsListOrTable(
                'hr_jobs_list'
            );
        });

        add_shortcode('hr_jobs', function ($args) {

            $this->setFilter($args);

            return $this->displayJobsListOrTable(
                'hr_jobs'
            );
        });

        add_action('wp_ajax_hr_search_jobs', [
            $this,
            'searchJobs'
        ], 20);

        add_action('wp_ajax_nopriv_hr_search_jobs', [
            $this,
            'searchJobs'
        ], 10);
    }

    /**
     * setFilter
     *
     * @param $args
     * @return void
     */
    private function setFilter($args): void {

        $this->filter = [
            'departments'          => [],
            'employments'          => [],
            'company_location_ids' => [],
            'address'              => null,
            'internal_title'       => null,
            'page'                 => 1
        ];

        if (empty($args) || !is_array($args))
            return;

        if (!empty($args['departments'])) {
            $this->filter['departments'] = explode(';', $args['departments']);
        }

        if (!empty($args['employments'])) {
            $employmentsFilter = explode(';', $args['employments']);
            $this->filter['employments'] = $this->replaceHumanEmployments2Ids($employmentsFilter);
        }

        if (!empty($args['locations'])) {
            $this->filter['company_location_ids'] = explode(';', $args['locations']);
        }

        if (!empty($args['address'])) {
            $this->filter['address'] = $args['address'];
        }

        if (!empty($args['internal_title'])) {
            $this->filter['internal_title'] = $args['internal_title'];
        }
    }

    /**
     * displayGoogleMap
     *
     * @return string|null
     * @throws Exception
     */
    public function displayGoogleMap(): ?string {

        $args = [];

        $this->company = $this->company ?? $this->getCompany();

        if (count($this->company) > 0) {

            $template = 'jobsGoogleMapDeactivated';

            if ($this->company['overview_page']['show_map'] ?? false) {
                $template = 'jobsGoogleMap';

                $args = [
                    'jobsAsJson' => json_encode($this->filterJobsWithLocation($this->getHrJobs()['jobs'] ?? []))
                ];
            }

            return $this->loadTemplateForShortcode($template, $args);
        }

        return null;
    }

    /**
     * filterJobsWithLocation
     *
     * @param $jobs
     * @return array
     */
    private function filterJobsWithLocation($jobs): array {

        $postUrl = get_permalink($this->getOption('jobDetailPageId'));

        foreach ($jobs as $job) {
            foreach ($job['company_location_jobs'] as $location) {
                if (
                    isset($job['job_strings'][0]['title']) &&
                    isset($location['company_location']['lat']) &&
                    isset($location['company_location']['lng']) &&
                    isset($location['company_location']['id'])
                ) {
                    $filteredJobs[] = [
                        'jobId'           => $job['id'],
                        'jobTitle'        => $job['job_strings'][0]['title'],
                        'jobLocationId'   => $location['company_location']['id'],
                        'fullAddress'     => $location['company_location']['full_address'],
                        'jobTitleWithUrl' => $this->createJobDetailLink(
                            $postUrl,
                            wp_trim_words($job['job_strings'][0]['title'], 10, '...'),
                            $job['id'],
                            $location['company_location']['id']
                        ),
                        'position'        => [
                            'lat' => (float)$location['company_location']['lat'],
                            'lng' => (float)$location['company_location']['lng'],
                        ]
                    ];
                }
            }
        }

        return $filteredJobs ?? [];
    }

    /**
     * displayFilterOptions
     *
     * @return string|null
     * @throws Exception
     */
    public function displayFilterOptions(): ?string {

        $this->company = $this->company ?? $this->getCompany();

        if (count($this->company) > 0) {

            $companySettings = $this->getCompanySettings();

            $filter = $companySettings->jobTableFilterOptions;

            $template = $filter->list || $filter->search || $filter->department || $filter->employment
                ? 'filterOptions'
                : 'filterOptionsDeactivated';

            $args = [
                'showDepartmentList' => $filter->department,
                'showEmploymentList' => $filter->employment,
                'showLocationList'   => $filter->list,
                'showLocationSearch' => $filter->search,
                'departmentList'     => $companySettings->departmentList ?? [],
                'employmentList'     => $companySettings->employmentList ?? [],
                'locationList'       => $this->getFilteredLocations(($companySettings->locationList ?? []))
            ];

            return $this->loadTemplateForShortcode($template, $args);
        }

        return null;
    }

    /**
     * getCompanySettings
     *
     * @return object
     * @throws Exception
     */
    private function getCompanySettings(): object {

        $this->company = $this->company ?? $this->getCompany();

        $jobTableColumnOptions = new stdClass();
        $jobTableFilterOptions = new stdClass();

        if (count($this->company)) {

            $this->jobs = $this->getHrJobs();

            $tableOptions = [
                'location',
                'employment',
                'department',
            ];

            $filterOptions = [
                'list',
                'search',
                'employment',
                'department',
            ];

            $apiTableColumns = explode(';', $this->company['overview_page']['job_table_categories'] ?? '');

            $apiFilterOptions = explode(';', $this->company['overview_page']['filter'] ?? '');

            $key = array_search('category', $apiTableColumns);

            if ($key !== false)
                $apiTableColumns[$key] = 'department';

            foreach ($tableOptions as $tableOption) {
                $jobTableColumnOptions->{$tableOption} = in_array($tableOption, $apiTableColumns) ?? false;
            }

            foreach ($filterOptions as $filterOption) {
                $jobTableFilterOptions->{$filterOption} = in_array($filterOption, $apiFilterOptions) ?? false;
            }

            if ($jobTableFilterOptions->employment && isset($this->jobs['employment_type_list'])) {

                $language = substr($this->defaultLocale, 0, 2);

                foreach ($this->jobs['employment_type_list'] as $key => $employments) {

                    foreach ($employments as $languageKey => $employment) {

                        if (!empty($this->filter['employments'])) {

                            if ($languageKey === $language
                                && in_array($key, $this->filter['employments'])) {
                                $employmentList[$key] = $employment;
                                break;
                            } elseif(in_array($key, $this->filter['employments'])) {
                                $employmentList[$key] = $employments['de'];
                            }

                        } else {

                            if ($languageKey === $language) {
                                $employmentList[$key] = $employment;
                                break;
                            }

                            $employmentList[$key] = $employments['de'];
                        }
                    }
                }
            }
        }

        return (object)[
            'defaultLanguageId'     => ($this->company['language_id'] ?? 1),
            'departmentList'        => $this->jobs['department_list'] ?? [],
            'employmentList'        => $employmentList ?? [],
            'locationList'          => $this->company['company_locations'] ?? [],
            'jobTableFilterOptions' => $jobTableFilterOptions,
            'jobTableColumnOptions' => $jobTableColumnOptions,
            'jobsPagination'        => (object)[
                'hasPages'    => false,
                'currentPage' => 1,
                'totalPages'  => 0,
            ]
        ];
    }

    /**
     * displayJobsListOrTable
     *
     * @throws Exception
     */
    public function displayJobsListOrTable($currentShortcode): string {

        $loadTemplate = 'noJobsAvailable';

        $this->company = $this->company ?? $this->getCompany();
        $this->jobs = $this->getHrJobs();

        if (count($this->jobs['jobs'] ?? []) > 0) {

            $companySettings = $this->getCompanySettings();

            $companySettings->jobsPagination->hasPages =
                $this->jobs['pagination']['total'] > $this->jobs['pagination']['limit'];

            $companySettings->jobsPagination->totalPages =
                ceil($this->jobs['pagination']['total'] / $this->jobs['pagination']['limit']);

            $companySettings->jobsPagination->currentPage =
                $this->filter['page'];

            $postUrl = get_permalink($this->getOption('jobDetailPageId'));

            $jobs = [];

            foreach ($this->jobs['jobs'] as $job) {

                $jobData = (object)$job;

                $jobLocations = $this->jobLocation($job);
                $jobData->locations = $jobLocations['locationIdForUrl'];
                $jobData->locationTitle = $jobLocations['jobLocationTitle'];
                $jobData->locationIdForUrl = $jobLocations['locationIdForUrl'];
                $jobData->jobDetailPageUrl = $postUrl;

                $jobData->title = $jobData->job_strings[0]['title'] ?? __('not specified', HEYRECRUIT_OPTION_KEY_NAME);

                $jobData->titleWithJobUrl = $this->createJobDetailLink(
                    $postUrl,
                    $jobData->title,
                    $jobData->id,
                    $jobLocations['locationIdForUrl']
                );

                $jobs[] = $this->setJobData($jobData, $companySettings->jobTableColumnOptions);

            }

            $args = [
                'options'                      => $companySettings,
                'pagination'                   => $this->jobs['pagination'],
                'jobs'                         => $jobs,
                'departmentFilter'             => json_encode($this->filter['departments']),
                'departmentFilterActivated'    => (int)(count($this->filter['departments']) > 0),
                'employmentFilter'             => json_encode($this->filter['employments']),
                'employmentFilterActivated'    => (int)(count($this->filter['employments']) > 0),
                'locationFilter'               => json_encode($this->filter['company_location_ids']),
                'locationFilterActivated'      => (int)(count($this->filter['company_location_ids']) > 0),
                'addressFilter'                => $this->filter['address'],
                'addressFilterActivated'       => !empty((trim($this->filter['address']??''))),
                'internalTitleFilter'          => $this->filter['internal_title'],
                'internalTitleFilterActivated' => !empty((trim($this->filter['internal_title']??''))),
                'currentShortcode'             => $currentShortcode
            ];

            $loadTemplate = $currentShortcode === 'hr_jobs' ? 'jobsTable' : 'jobsList';
        }

        return $this->loadTemplateForShortcode($loadTemplate, $args ?? []);
    }

    /**
     * createJobDetailLink
     *
     * @param string $postUrl
     * @param string $linkTitle
     * @param string $jobId
     * @param string|null $jobLocationId
     *
     * @return string
     */
    public function createJobDetailLink(
        string $postUrl,
        string $linkTitle,
        string $jobId,
        string $jobLocationId = null): string {

        $permalinkWithParams = add_query_arg([
            'jobId'      => $jobId,
            'locationId' => $jobLocationId,
        ], $postUrl);

        return '<a href = "' . esc_url($permalinkWithParams) . '" target = "_blank" >' . $linkTitle . '</a>';
    }

    /**
     * searchJobs
     *
     * @return void
     * @throws Exception
     */
    public function searchJobs(): void {

        $this->filter = [
            'language'             => 'de',
            'departments'          => $this->decodePostFilter2Array(($_POST['department'] ?? '')),
            'employments'          => $this->decodePostFilter2Array(($_POST['employment'] ?? '')),
            'company_location_ids' => $this->decodePostFilter2Array(($_POST['location'] ?? '')),
            'address'              => $_POST['address'] === 'undefined' || $_POST['address'] === ''
                ? '' : filter_var($_POST['address'], FILTER_SANITIZE_STRING),
            'internal_title'       => $_POST['internal_title'] === 'undefined' || $_POST['internal_title'] === ''
                ? '' : filter_var($_POST['internal_title'], FILTER_SANITIZE_STRING),
            'page'                 =>
                !empty($_POST['page']) ? filter_var($_POST['page'], FILTER_VALIDATE_INT) : 1,
        ];

        $this->jobs = $this->getHrJobs();

        $currentShortcode = !empty($_POST['currentShortcode']) && $_POST['currentShortcode'] != 'undefined'
            ? filter_var($_POST['currentShortcode'], FILTER_SANITIZE_STRING)
            : $this->currentShortcode;

        $this->currentShortcode = in_array($currentShortcode, [
            'hr_jobs',
            'hr_jobs_list'
        ])
            ? $currentShortcode : $this->currentShortcode;

        wp_send_json(([
            'jobs'                 => $this->displayJobsListOrTable($this->currentShortcode),
            'jobsDataForGoogleMap' => $this->filterJobsWithLocation($this->jobs['jobs'] ?? [])
        ] ?? []), 200);

        wp_die();
    }

    /**
     * setJobData
     *
     * @param object $jobData
     * @param object $options
     *
     * @return object
     */
    private function setJobData(object $jobData, object $options): object {

        $job = new stdClass();

        $job->id = $jobData->id;
        $job->locationId = $jobData->locationIdForUrl;

        $job->title = $jobData->title;
        $job->titleWithJobUrl = $jobData->titleWithJobUrl;

        $job->jobDetailPageUrl = add_query_arg([
            'jobId'      => $job->id,
            'locationId' => $job->locationId,
        ], $jobData->jobDetailPageUrl);

        if ($options->department) {

            $job->department = $jobData->job_strings[0]['department'] ?? null;
        }

        if ($options->employment) {

            $job->employment = str_replace(',', ', ', ($jobData->job_strings[0]['employment'] ?? ''));
        }

        if ($options->location) {

            $job->locationTitle = $jobData->locationTitle;
        }

        return $job;
    }

    /**
     * decodePostFilter2Array
     *
     * @param string $postFilter
     * @return array
     */
    private function decodePostFilter2Array(string $postFilter): array {

        if (empty($postFilter)) {
            return [];
        }

        $postFilter = filter_var($postFilter, FILTER_SANITIZE_STRING);
        $postFilter = html_entity_decode($postFilter);
        $postFilter = stripslashes($postFilter);

        $postFilter = json_decode($postFilter, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $postFilter;
    }

    /**
     * getFilteredLocations
     *
     * @param array $companyLocationList
     * @return array
     */
    private function getFilteredLocations(array $companyLocationList): array {

        if (empty($this->filter['company_location_ids']))
            return $companyLocationList;

        return array_intersect_key($companyLocationList, array_flip($this->filter['company_location_ids']));
    }

    /**
     * replaceHumanEmployments2Ids
     *
     * @param array $employmentFilter
     * @return array
     */
    private function replaceHumanEmployments2Ids(array $employmentFilter): array {

        return array_filter(array_map(function ($employmentString) {

            return $this->employments[strtolower(trim($employmentString))] ?? null;

        }, $employmentFilter), function ($value) {

            return !empty($value);
        });
    }
}