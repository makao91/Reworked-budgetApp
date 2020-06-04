$.validator.addMethod('validPassword',
  function(value, element, param) {
    if(value != ''){
      if(value.match(/.*[a-z]+.*/i) == null){
        return false;
      }
      if(value.match(/.*\d.*/) == null){
        return false;
      }
    }
    return true;
  },
  'Musi zawierać conajmniej jedną literę i jedną cyfrę.'
);
function BuildChartIncome(labels, values, chartTitle) {
  var ctx = document.getElementById("myChart2").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels, // Our labels
      datasets: [{
        label: chartTitle, // Name the series
        data: values, // Our values
        backgroundColor: [ // Specify custom colors
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'

        ],
        hoverBackgroundColor: '#CCCCCC',
        hoverBorderColor: '#666666',
        borderColor: [ // Add custom color borders
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderWidth: 1 // Specify bar border width
      }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false,
       // Add to prevent default behavior of full-width/height
      title: {
				display: true,
				text: 'Procentowy udział przychodów',
        fontSize: 25
			}
    }
  });
  return myChart;
}
function BuildChartExpense(labels, values, chartTitle) {
  var ctx = document.getElementById("myChart").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels, // Our labels
      datasets: [{
        label: chartTitle, // Name the series
        data: values, // Our values
        backgroundColor: [ // Specify custom colors
          "#92a8d1", "#034f84","#3cba9f","#e8c3b9","#c45850", "#f7786b", "#c94c4c", "#50394c", "#618685", "#4040a1", "#36486b", "#d64161", "#ff7b25", "#feb236", "#6b5b95", "#e3eaa7", "#b5e7a0", "#86af49", "#405d27", "#dac292", "#b9936c"
        ],
        hoverBackgroundColor: '#CCCCCC',
        hoverBorderColor: '#666666',
        borderColor: [ // Add custom color borders
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderWidth: 1 // Specify bar border width
      }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false,
       // Add to prevent default behavior of full-width/height
      title: {
				display: true,
				text: 'Procentowy udział wydatków',
        fontSize: 25
			}
    }
  });
  return myChart;
}
