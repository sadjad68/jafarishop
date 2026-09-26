
    function checkMobile(e) {

    const mobile_val = e.target.value;
    var mobileMaterial = new RegExp(/^(\+98|0098|98|0)?9\d{9}$/);
    const mobile = mobile_val.replace(/([۰-۹])/g, (token) =>
    String.fromCharCode(token.charCodeAt(0) - 1728)
    );
    if (!mobile_val) {
        Swal.fire({
            icon: 'info',
            text: "وارد کردن شماره همراه الزامیست",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    return false;
}
    if (mobile_val.length !== 11) {
        Swal.fire({
            icon: 'info',
            text: "شماره همراه نامعتبر است",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    e.target.value = "";

    return false;
}
    if (!mobileMaterial.test(mobile)) {
        Swal.fire({
            icon: 'info',
            text: "شماره همراه نامعتبر است",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    e.target.value = "";

    return false;
}
}

    function warnRequired(fieldName)
    {
        Swal.fire({
            icon: 'info',
            text: fieldName + " " + "اجباری است. ",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    }
