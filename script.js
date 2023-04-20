// get the select element
var selectElement = document.getElementById("select");

// get the selected option
var selectedOption = selectElement.options[selectElement.selectedIndex];

// set the value of the select element to the selected option's value
selectElement.value = selectedOption.value;
