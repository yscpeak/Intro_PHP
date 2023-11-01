/******w**************
    
    Assignment 4 Javascript
    Name: Yi Siang Chang
    Date: 2023-10-21
    Description: AJAX, JSON, and Open Data

*********************/

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const resultsContainer = document.getElementById('results');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const parkName = document.getElementById('parkName').value;
        searchParks(parkName);
    });

    function searchParks(parkName) {
        const apiUrl = `https://data.winnipeg.ca/resource/tx3d-pfxq.json?$where=lower(park_name) LIKE lower('%${parkName}%')&$order=park_name&$limit=100`;
        const encodedURL = encodeURI(apiUrl);
    fetch(encodedURL)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultsContainer.innerHTML = '<p>No parks found with that name</p>';
            } else {
                let resultsHtml = '<ul>';
                data.forEach(park => {
                    const location = park.location && park.location.latitude && park.location.longitude
                        ? `<a href="https://www.google.com/maps?q=${park.location.latitude},${park.location.longitude}" 
                                target="_blank"><img src="map-icon.png" alt="Map" style="width: 20px; height: 20px;"/></a>`
                        : 'Location not available';
                    const areaHectares = park.area_in_hectares ?
                        `${parseFloat(park.area_in_hectares).toFixed(2)} Hectares` : 'Area not available';
                    resultsHtml += `<li>${park.park_name} - Location: ${location}, Total Area in Hectares: ${areaHectares}</li>`;
                });
                resultsHtml += '</ul>';
                resultsContainer.innerHTML = resultsHtml;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            resultsContainer.innerHTML = '<p>Error fetching data. Please try again later.</p>';

});
    }
});

