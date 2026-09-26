  function changeValue(id, increment) {
    var inputField = document.getElementById(id);
    var value = parseInt(inputField.value, 10);
    value = isNaN(value) ? 0 : value;
    value += increment;
    inputField.value = value < 1 ? 1 : value;
  }