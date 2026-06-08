import "./bootstrap";
import Alpine from "alpinejs";
import Chart from "chart.js/auto"; // Mengimpor Chart.js

window.Alpine = Alpine;
window.Chart = Chart; // Membuat Chart tersedia secara global

Alpine.start();
