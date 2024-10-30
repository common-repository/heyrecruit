let map,
markers = [],
mapCenter = {lat: 50.253850, lng: 8.641741},
mapZoom = 5,
currentInfoWindow = null,
mapElement = document.getElementById('jobsInGoogleMap'),
jobData= args['job_data'] ? JSON.parse(args['job_data']) : [];

/**
 * initJobsInGoogleMap
 */
function initJobsInGoogleMap() {

    if (!mapElement) return;

    map = new google.maps.Map(mapElement, {
        zoom: mapZoom,
        center: mapCenter,
        mapId: "jobsInGoogleMap"
    });

    jobData && jobData.length > 0 && updateJobMarkers(jobData)
}

/**
 * addMarkers
 * @param jobData
 */
const addMarkers = (jobData) => {

    if (!map) return;

    const groupedData = {};

    jobData.forEach(job => {
        const key = `${job.position.lat},${job.position.lng}`;
        if (!groupedData[key]) {
            groupedData[key] = {
                position: job.position,
                fullAddress: job.fullAddress,
                jobs: []
            };
        }
        groupedData[key].jobs.push(job);
    });

    const generateSVGIcon = (color) => {
        return `
                <svg width="24" height="36" viewBox="0 0 24 36" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 0C5.373 0 0 5.373 0 12C0 22 12 36 12 36C12 36 24 22 24 12C24 5.373 18.627 0 12 0ZM12 18C8.686 18 6 15.314 6 12C6 8.686 8.686 6 12 6C15.314 6 18 8.686 18 12C18 15.314 15.314 18 12 18Z"
                    fill="${color}"/>
                </svg>`;
    }

    markers.forEach(marker => {
        marker.setMap(null);
    });

    markers = [];

    Object.values(groupedData).forEach(location => {
        const iconDiv = document.createElement('div');

        iconDiv.innerHTML = generateSVGIcon(args['marker_color']);

        const marker = new google.maps.marker.AdvancedMarkerElement({
            map,
            position: location.position,
            title: location.jobs.map(job => job.jobTitle).join(', '),
            content: iconDiv
        });

        const infoWindowContent = document.createElement('div');

        infoWindowContent.classList.add('hr-google-map-marker-info-window-content');
        infoWindowContent.innerHTML = `
                    <div class="hr-google-map-marker-info-window-address-info">
                        <p>${location.fullAddress}</p>
                    </div>
                    <hr>
                    ${location.jobs.map(job => `
                    <div class="hr-google-map-marker-info-window-job-info">
                        <p>${job.jobTitleWithUrl}</p>
                    </div>
                    `).join('<hr>')}`;

        const infoWindow = new google.maps.InfoWindow({
            content: infoWindowContent,
            pixelOffset: new google.maps.Size(0, -30),
            maxWidth: 300,
            zIndex: 1000,
            disableAutoPan: false,
            closeButton: true
        });

        markers.push(marker);

        marker.addListener('click', function () {

            if (currentInfoWindow)
                currentInfoWindow.close();

            infoWindow.open(map, marker);

            currentInfoWindow = infoWindow;
        });
    });
}

/**
 * updateJobMarkers
 * @param jobData
 */
const updateJobMarkers = (jobData) => {

    if (!mapElement) return;

    map.setCenter(mapCenter);
    map.setZoom(mapZoom);
    addMarkers(jobData);
}