const ctx = document.getElementById('myChart') ? document.getElementById('myChart').getContext('2d') : null;
const registrationsChartData = document.getElementById('registrations-chart-data').getAttribute('data-registrations');
const visitorsChartData = document.getElementById('visitors-data').getAttribute('data-visitors');

// JSON adatok feldolgozása
const registrationsData = JSON.parse(registrationsChartData);
const visitorsData = JSON.parse(visitorsChartData);
// Év összes hónapjának létrehozása
const allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Hónapok és regisztrációk külön változókba rendezése
const months = allMonths.map(month => registrationsData[month] || 0); // Ha nincs adat, akkor 0

if (ctx) {

    // Chart.js diagram létrehozása
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: allMonths, // Minden hónap megjelenítése, akkor is, ha nincs adat
            datasets: [{
                label: 'Regisztrációk',
                data: months, // Regisztrációk száma hónapok szerint
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

const ctx2 = document.getElementById('myChart_2') ? document.getElementById('myChart_2').getContext('2d') : null;

if (ctx2) {
    // Adatok előkészítése a doughnut diagramhoz
    const registrations = months.reduce((sum, value) => sum + value, 0); // Összes regisztráció száma
    const visitors = visitorsData.length; // Látogatók száma

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Regisztrációk', 'Látogatók'],
            datasets: [{
                label: 'Regisztrácók és látogatók aránya',
                data: [registrations, visitors],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)', // Regisztrációk színe
                    'rgba(54, 162, 235, 0.2)'   // Látogatók színe
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
