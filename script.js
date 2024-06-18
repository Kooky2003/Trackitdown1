async function fetchData() {
    try {
      const response = await fetch('http://localhost/Project/codes/datafetch.php');
      if (!response.ok) {
        throw new Error(`Failed to fetch data: ${response.status} ${response.statusText}`);
      }
      const data = await response.json();
  
      console.log(data);
  
      processData(data);
    } catch (error) {
      console.error('Error fetching data:', error.message);
    }
  }
  
  function processData(data) {
    if (!Array.isArray(data)) {
      console.error('Invalid data format');
      return;
    }
    const chartData = {
      labels: data.map(item => item.taskname),
      data: data.map(item => item.timetaken),
    };
  
    createDoughnutChart(chartData);
    populateUl(chartData);
  }
  
  function createDoughnutChart(chartData) {
    const myChart = document.querySelector(".my-chart");
    if (!myChart) {
      console.error('.my-chart element not found');
      return;
    }
  
    new Chart(myChart, {
      type: "doughnut",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Time spent",
            data: chartData.data,
          },
        ],
      },
      options: {
        borderWidth: 10,
        borderRadius: 2,
        hoverBorderWidth: 0,
        plugins: {
          legend: {
            display: false,
          },
        },
        tooltips: {
          enabled: false,
        },
      },
    });
  }
  
  const ul = document.querySelector(".programming-stats .details ul");
  
  function populateUl(chartData) {
    if (!Array.isArray(chartData.labels) || !Array.isArray(chartData.data) || chartData.labels.length !== chartData.data.length) {
      console.error('Invalid chart data');
      return;
    }
    if (!ul) {
      console.error('.programming-stats .details ul element not found');
      return;
    }
  
    ul.innerHTML = ''; // Clear previous data
    chartData.labels.forEach((label, i) => {
      let li = document.createElement("li");
      li.innerHTML = `${label}: <span class='percentage'>${chartData.data[i]}sec</span>`;
      ul.appendChild(li);
    });
  }
  
  fetchData();
  