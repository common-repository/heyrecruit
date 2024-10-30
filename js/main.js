(function ($, args) {

        "use strict"; // Start of use strict

        /**
         * on document ready
         */
        $(document).ready(function () {
            removeAddressAndLocationFilterIfAddressFilterActive()
            hrChangePage()
            hrSendJobFilter()
            hrSendApplicant()
            changeLocation()
            openModal();
            loadDeleteUploadFields()
        })
        
        /**
         * hrSendJobFilter
         */
        const hrSendJobFilter = () => {

            $('#hrSendJobFilter').off('click').on('click', function () {

                let heyrecruitJobsElement = document.getElementById('heyrecruit_jobs'),
                    currentShortcode = '',
                    departmentOptions = '',
                    departmentFilterIsActivated = '',
                    employmentOptions = '',
                    employmentFilterIsActivated = '',
                    locationOptions = '',
                    locationFilterIsActivated = '',
                    address = '',
                    addressFilterIsActivated = '',
                    internalTitle = '',
                    internalTitleFilterIsActivated = ''

                if (heyrecruitJobsElement) {

                    departmentFilterIsActivated = heyrecruitJobsElement
                        .getAttribute('data-department-filter-activated');
                    departmentFilterIsActivated = (departmentFilterIsActivated === '1')
                    departmentOptions = JSON.stringify(
                        getSelectValuesAsArray('department', departmentFilterIsActivated)
                    );

                    employmentFilterIsActivated = heyrecruitJobsElement
                        .getAttribute('data-employment-filter-activated');
                    employmentFilterIsActivated = (employmentFilterIsActivated === '1')
                    employmentOptions = JSON.stringify(
                        getSelectValuesAsArray('employment', employmentFilterIsActivated)
                    );

                    locationFilterIsActivated = heyrecruitJobsElement
                        .getAttribute('data-location-filter-activated');
                    locationFilterIsActivated = (locationFilterIsActivated === '1')
                    locationOptions = JSON.stringify(
                        getSelectValuesAsArray('location', locationFilterIsActivated)
                    );

                    addressFilterIsActivated = (heyrecruitJobsElement
                        .getAttribute('data-address-filter-activated') === '1');

                    address = addressFilterIsActivated
                        ? heyrecruitJobsElement.getAttribute('data-address-filter')
                        : $('#address').val()

                    internalTitleFilterIsActivated = heyrecruitJobsElement
                        .getAttribute('data-internal-title-filter-activated');

                    if (internalTitleFilterIsActivated)
                        internalTitle = heyrecruitJobsElement.getAttribute('data-internal-title-filter');

                    heyrecruitJobsElement.innerHTML = '<div class="infoBox">' + args['hr_loading_info_text'] + '</div>'
                    currentShortcode = heyrecruitJobsElement.getAttribute('data-current-shortcode')

                    heyrecruitJobsElement.setAttribute('data-department-filter', departmentOptions);
                    heyrecruitJobsElement.setAttribute('data-employment-filter', employmentOptions);

                    heyrecruitJobsElement.setAttribute('data-location-filter', locationOptions);

                    addressFilterIsActivated &&
                    heyrecruitJobsElement.setAttribute('data-address-filter', address);

                    internalTitleFilterIsActivated
                    && heyrecruitJobsElement.setAttribute('data-internal-title-filter', internalTitle);
                }

                let data = {
                    action: 'hr_search_jobs',
                    page: 1,
                    department: departmentOptions,
                    employment: employmentOptions,
                    location: locationOptions,
                    address: address,
                    internal_title: internalTitle,
                    currentShortcode: currentShortcode
                }

                fetchData(args['hr_ajax_url'], data, displaySearchData)
            })
        }

        /**
         * hrSendJobFilter
         */
        const hrChangePage = () => {

            $('.hr-page-numbers').off('click').on('click', function () {

                let heyrecruitJobsElement = document.getElementById('heyrecruit_jobs'),
                    currentShortcode = '',
                    departmentOptions = '',
                    employmentOptions = '',
                    locationOptions = '',
                    address = '',
                    addressFilterIsActivated = '',
                    internalTitle = ''

                if (heyrecruitJobsElement) {

                    currentShortcode = heyrecruitJobsElement.getAttribute('data-current-shortcode')

                    departmentOptions = heyrecruitJobsElement.getAttribute('data-department-filter');
                    employmentOptions = heyrecruitJobsElement.getAttribute('data-employment-filter');
                    locationOptions = heyrecruitJobsElement.getAttribute('data-location-filter');

                    addressFilterIsActivated = (heyrecruitJobsElement
                        .getAttribute('data-address-filter-activated') === '1');

                    address = addressFilterIsActivated
                        ? heyrecruitJobsElement.getAttribute('data-address-filter')
                        : $('#address').val()

                    internalTitle = heyrecruitJobsElement.getAttribute('data-internal-title-filter');

                }

                let data = {
                    action: 'hr_search_jobs',
                    page: $(this).data('page'),
                    department: departmentOptions,
                    employment: employmentOptions,
                    location: locationOptions,
                    address: address,
                    internal_title: internalTitle,
                    currentShortcode: currentShortcode
                }

                fetchData(args['hr_ajax_url'], data, displaySearchData)
            })
        }

        /**
         * getSelectValuesAsArray
         *
         * @param {string} selectId - Die ID des select-Feldes
         * @param {boolean} filterIsActivated - Filter aktiv
         * @returns {any[]} - Array der Werte oder null, wenn das select-Feld nicht existiert
         */
        const getSelectValuesAsArray = (selectId, filterIsActivated) => {

            let selectElement = document.getElementById(selectId);

            if (selectElement && selectElement.value) {

                if (selectElement.value === 'all' && filterIsActivated) {
                    let options = Array.from(selectElement.options);

                    return options.slice(1).map(option => option.value);
                } else if (selectElement.value === 'all' && !filterIsActivated) {
                    return [];
                } else {
                    return [selectElement.value]
                }
            }

            return [];
        };

        /**
         * refreshJobsInGoogleMap
         *
         * @param jobsDataForGoogleMap
         */
        const refreshJobsInGoogleMap = (jobsDataForGoogleMap) => {

            document.getElementById('jobsInGoogleMap') !== null
            && jobsDataForGoogleMap
            && updateJobMarkers(jobsDataForGoogleMap)
        }

        /**
         * displaySearchData
         *
         * @param data {object}
         */
        const displaySearchData = (data) => {


            let heyrecruitJobsElement = document.getElementById('heyrecruit_jobs');
            heyrecruitJobsElement.innerHTML = $(data.jobs).html();

            hrChangePage()
            hrSendJobFilter()

            refreshJobsInGoogleMap(data.jobsDataForGoogleMap)
        }

        /**
         * loadDeleteUploadFields
         */
        const loadDeleteUploadFields = () => {

            $('.select_file_upload').off('change').on('change', function () {

                let selectedOption = $(this).find(':selected'),
                    selectedOptionValue = selectedOption.val(),
                    dataFieldName = selectedOption.data('field-name'),
                    dataFormId = selectedOption.data('form-id'),
                    dataValue = selectedOption.data('value'),
                    dataName = selectedOption.data('name'),
                    newFileInputHTML = `<div class="hr_form">
                <label class="hrUploadFormText" for="${dataFieldName}">${dataFieldName}
                    <input type="file" name="files[]" class="file_upload"
                           data-type="${dataValue}"
                           data-name="${dataName}"
                           data-question-id="${dataFormId}" />
                    <span class="delete_file_upload" id="${selectedOptionValue}">x</span>
                </label>
            </div>`;

                $('#upload_fields_' + dataFormId).append(newFileInputHTML);

                $(this).val(null);

                $('.delete_file_upload').off('click').on('click', function () {

                    let fieldValue = $(this).closest('.hr_form').find('input[type="file"]').val();

                    if (fieldValue) {
                        confirm("Möchten Sie dieses Datei wirklich entfernen?")
                        && $(this).closest('.hr_form').remove();
                    } else {
                        $(this).closest('.hr_form').remove();
                    }

                });

            });
        }

        /**
         * handleFormData
         *
         * @param data
         */
        const handleFormData = (data) => {

            $.ajax({
                type: 'POST',
                url: args['hr_ajax_url'],
                data: {
                    action: 'hr_send_applicant',
                    data: data
                },

                success: function (response) {

                    if (response.status === 'error')

                        displayErrorMessages(response)

                    else if (response.status === 'success' && typeof response.data.applicant_job_id != 'undefined')

                        window.location.href = confirmPage;

                },
                error: function (xhr) {

                    console.error(xhr.responseText);
                },
                complete: function () {

                    $('#saveApplicant').prop('disabled', false);
                }
            });
        }

        /**
         * hrSendApplicant
         */
        const hrSendApplicant = () => {

            $('#saveApplicant').off('click').on('click', function (event) {
                event.preventDefault();

                $('.error-message').remove()

                $('#saveApplicant').prop('disabled', true);

                $('#hrJobApplication').serializeFormJSON(handleFormData);
            });
        }

        /**
         * displayErrorMessages
         *
         * @param response
         */
        const displayErrorMessages = (response) => {

            if (response.detail === 'Conflict') {
                alert(response.errors)
                return;
            }
            $.each(response.errors, function (inputName, errorMessages) {
                let inputElement = $('[name="' + inputName + '"]');
                let errorDiv = $('<div class="error-message"></div>');

                errorDiv.append(errorMessages[0]);
                if (inputName !== "consent_form_accepted") {
                    inputElement.after(errorDiv);
                } else {
                    inputElement.parent().after(errorDiv);
                }
            });
        }

        /**
         * serializeFormJSON
         *
         * @param callback
         */
        $.fn.serializeFormJSON = function (callback) {

            let data = {},
                form = this,
                formData = form.serializeArray(),
                totalFiles = 0,
                filesProcessed = 0;

            $.each(formData, function () {

                let inputType = form.find('[name="' + this.name + '"]')[0].type;

                if (inputType === 'date' && this.value.length > 0) {
                    let inputDate = this.value,
                        parts = inputDate.split('-');

                    data[this.name] = parts[2] + '.' + parts[1] + '.' + parts[0];
                } else {
                    if (data[this.name] !== undefined) {
                        if (!data[this.name].push) {
                            data[this.name] = [data[this.name]];
                        }
                        data[this.name].push(this.value || '');
                    } else {
                        data[this.name] = this.value || '';
                    }
                }
            });

            data['files'] = [];

            form.find('input[type="file"]').each(function () {
                let files = $(this)[0].files;

                if (files.length > 0) {
                    totalFiles++;

                    for (let i = 0; i < files.length; i++) {

                        let fileName = files[i].name,
                            fileType = files[i].type,
                            fileInternType = $(this).data('type'),
                            questionId = $(this).data('question-id'),
                            reader = new FileReader();

                        reader.onload = function (event) {
                            let base64Data = event.target.result,
                                dataPrefixIndex = base64Data.indexOf(',');

                            if (dataPrefixIndex !== -1)
                                base64Data = base64Data.slice(dataPrefixIndex + 1);

                            data['files'].push({
                                'data': base64Data,
                                'content_type': fileType,
                                'name': fileName,
                                'type': fileInternType,
                                'question_id': questionId
                            });

                            filesProcessed++;

                            filesProcessed === totalFiles && callback(data);

                        };

                        reader.readAsDataURL(files[i]);
                    }
                }
            });

            totalFiles === 0 && callback(data);
        };

        /**
         * removeAddressAndLocationFilterIfAddressFilterActive
         */
        const removeAddressAndLocationFilterIfAddressFilterActive = () => {

            const heyrecruitJobsElement = document.getElementById('heyrecruit_jobs'),
                filtersContainer = document.querySelector('.hr_jobs_filters')

            if (filtersContainer
                && heyrecruitJobsElement?.getAttribute('data-address-filter-activated') === '1') {
                filtersContainer.querySelector('label[for="address"]')?.remove()
                filtersContainer.querySelector('label[for="location"]')?.remove()
            }
        }

        /**
         * changeLocation
         */
        const changeLocation = () => {

            $('#changeLocation').off('change').on('change', function () {
                $('#locationId').val($(this).val())
            })
        }

        /**
         * fetchData
         *
         * @param url {string}
         * @param data {object}
         * @param after {function|boolean}
         * @param method {string}
         */
        const fetchData = (url, data = {}, after = false, method = 'POST') => {

            fetch(url, {
                method: method,
                mode: 'same-origin',
                cache: 'force-cache',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    // 'X-WP-Nonce': WP_NONCE,
                },
                redirect: 'follow',
                referrerPolicy: 'same-origin',
                body: postData(data),

            })
                .then(response => response.json())
                .then(data => {

                    if (after)
                        after(data)

                    if (data.result === 'error' && data.errors)
                        alert('Es trat ein Fehler bei der Suche auf.')

                })
                .catch(error => {
                    console.error('Error:', error)
                })
        }

        /**
         * postData
         *
         * @param data {object}
         * @returns {URLSearchParams}
         */
        const postData = (data) => {
            return new URLSearchParams(Object.keys(data).map(key => [key, data[key]]));
        }

        /**
         * openModal
         */
        const openModal = () => {
            $('.openModal').off('click').on('click', function () {

                $('#modal' + $(this).data('id')).fadeIn();
                closeModal();
            })
        }

        /**
         * closeModal
         */
        const closeModal = () => {
            $('.closeModal').off('click').on('click', function () {

                $('#modal' + $(this).data('id')).fadeOut();
            })
        }
    }
)(jQuery, args)