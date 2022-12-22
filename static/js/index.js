
function renderBarChart(series, title, x_axis_label, y_axis_label){

document.getElementById("chartDiv").style.display = "block";
document.getElementById("chartHideDiv").style.display = "block";
document.getElementById("hide").style.display = "block";
document.getElementById("chartDiv").scrollIntoView();
	let bars = [];
	let xValues = [];
	let yValues = [];
	var barColors= ['#00429d', '#2e59a8', '#4771b2', '#5d8abd', '#73a2c6', '#8abccf', '#a5d5d8', '#c5eddf', '#ffffe0']

	Object.entries(series).forEach(
    	([key, value]) => bars.push({x: key, y: value}, xValues.push(key), yValues.push(value))
	);

	new Chart("chartDiv", {
	  type: "bar",
	  data: {
	  	label: "",
	    labels: xValues,
	    datasets: [{
	      backgroundColor: barColors,
	      data: yValues
	    }]
	  },
			  options: {
			  	legend: {
			  		display: false
			  	},
			  	responsive: true,
			  	maintainAspectRatio: true,
			  	aspectRatio: 2,
			    scales: {
				    xAxes: [{
				      scaleLabel: {
				        display: true,
				        labelString: x_axis_label
				      }
				    }],
				    yAxes: [{
				      scaleLabel: {
				        display: true,
				        labelString: y_axis_label
				      }
				    }]
				},
	    		plugins: {
				    legend: {
				    	display: false,
				    },
	      			labels: {
	        			// render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
	        			render: 'value',
	        			fontColor: '#000000',
	        			// color: '#36A2EB'
	        		},
	        	},
			  	events: [],
			    title: {
			      display: true,
			      text: title,
			    }
			  }
	});
}

function renderPieChart(series, title, x_axis_label, y_axis_label){

	var barColors = [
	  "#b91d47",
	  "#00aba9",
	  "#2b5797",
	  "#e8c3b9",
	  "#1e7145"
	];

document.getElementById("chartDiv").style.display = "block";
document.getElementById("chartHideDiv").style.display = "block";
document.getElementById("hide").style.display = "block";
document.getElementById("chartDiv").scrollIntoView();
	let bars = [];
	let xValues = [];
	let yValues = [];

	Object.entries(series).forEach(
    	([key, value]) => bars.push({x: key, y: value}, xValues.push(key), yValues.push(value))
	);
		new Chart("chartDiv", {
		  type: "pie",
		  data: {
		    labels: xValues, // provide legend labels
		    datasets: [{
		      backgroundColor: barColors,
		      data: yValues,
		      label: xValues
		    }]
		  },
		  options: {
    		plugins: {
      			labels: {
        			// render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
        			render: 'label',
        			fontColor: '#000000',
        			// color: '#36A2EB'
        		}
        	},
		  	events: [],
		    title: {
		      display: true,
		      text: title,
		    }
		  }
		});
}

function hideChart(){
	document.getElementById("chartDiv").style.display = "none";
	document.getElementById("chartHideDiv").style.display = "none";
	document.getElementById("hide").style.display = "none";
}
