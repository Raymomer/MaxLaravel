
console.log(localStorage.getItem('token'))


function login() {
    console.log("login now")

    reqParameter = {
        account: $('#inputAccount').val(),
        password: $('#inputPassword').val()
    };

    $.ajax({
        url: "http://127.0.0.1:8000/Max/api/user/login?",
        method: "POST",
        data: reqParameter
    }).done(res => {

        if (res.status) {
            localStorage.setItem('token', res.payload.token)
            location.href = "http://127.0.0.1:8000/Max"
        }


    }).error(err => {
        
        localStorage.removeItem('token');
        alert("Login Error!");
    })




}