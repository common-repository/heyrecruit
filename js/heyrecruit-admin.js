document.addEventListener('DOMContentLoaded', function () {

    let resetButton = document.getElementById('reset_page_ids');
    if (resetButton) {
        resetButton.addEventListener('click', function (event) {

            if (confirm('Möchten Sie wirklich alle Seiten (Job-Übersichtsseite, Job-Detailseite & Bestätigungsseite) neu generieren?')) {

                let confirmHiddenField = document.getElementById('heyrecruitConfirmResetPageIds');

                if (confirmHiddenField)
                    confirmHiddenField.value = '1';

            } else
                event.preventDefault();
        });
    }
});