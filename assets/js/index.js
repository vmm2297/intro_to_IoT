var tempCtx = document.getElementById("temp-chart").getContext('2d');
var tempChart = new Chart(tempCtx, {
    type: 'radar',
    data: {
        labels: ["1200", "0100", "0200", "0300", "0400", "0500", "0600", "0700", "0800", "0900", "1000", "1100"],
        datasets: [{
            label: 'Average Temperature by Hour - Morning',
            data: temperatureDataDay,
            backgroundColor:
                'rgba(255, 230, 64, 0.5)',
            borderColor:
                'rgba(255, 230, 64, 1)',
        },
        {
            label: 'Average Temperature by Hour - Afternoon',
            data: temperatureDataNight,
            backgroundColor:
                'rgba(12, 12, 56, 0.5)',
            borderColor:
                'rgba(12, 12, 56, 1)',
        }]
    },
    options: {
        scales: {
            ticks: {
                min: temperatureMin,
                max: temperatureMax,
            }
        },
        responsive: false
    }
});

var soundCtx = document.getElementById("sound-chart").getContext('2d');
var soundChart = new Chart(soundCtx, {
    type: 'radar',
    data: {
        labels: ["1200", "0100", "0200", "0300", "0400", "0500", "0600", "0700", "0800", "0900", "1000", "1100"],
        datasets: [{
            label: 'Average Sound Level by Hour - Morning',
            data: soundDataDay,
            backgroundColor:
                'rgba(255, 230, 64, 0.5)',
            borderColor:
                'rgba(255, 230, 64, 1)',
        },
        {
            label: 'Average Sound Level by Hour - Afternoon',
            data: soundDataNight,
            backgroundColor:
                'rgba(12, 12, 56, 0.5)',
            borderColor:
                'rgba(12, 12, 56, 1)',
        }]
    },
    options: {
        scales: {
            ticks: {
                min: soundMin,
                max: soundMax,
            }
        },
        responsive: false
    }
});

function update(jscolor) {
  document.getElementById("color").value = jscolor;
}

function apply() {
  document.getElementById("smt").click();
}