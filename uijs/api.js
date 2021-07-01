$(document).ready(function () {
  // *LOGIN FORM
  $("#loginForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const auth = new Auth(data);
      const res = await auth.login();
      if (res.status == 1) {
        $("#modalLoginAvatar").modal("hide");
        showModal({
          title: "Logged In",
          body: `<strong>${res.data.userId}</strong> Logged in Successfully`,
          type: "success",
        });
        // redirect
        const returnUrl = new URL(location.href).searchParams.get(
          "redirectUrl"
        );
        setTimeout(function () {
          location.href = returnUrl || "dashboard";
        }, 1000);
      } else {
        $("#modalLoginAvatar").modal("hide");
        showModal({
          title: "Login Unsuccessful",
          body: res.status_message,
          type: "danger",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // * ADMIN LOGIN FORM
  $("#adminLoginForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const auth = new Auth(data);
      const res = await auth.adminLogin();
      if (res.status == 1) {
        showModal({
          title: "Logged In",
          body: `<strong>${res.data.adminId}</strong> Logged in Successfully`,
          type: "success",
        });
        // redirect
        const returnUrl = new URL(location.href).searchParams.get(
          "redirectUrl"
        );
        setTimeout(function () {
          location.href = returnUrl || "dashboard";
        }, 1000);
      } else {
        showModal({
          title: "Login Unsuccessful",
          body: res.status_message,
          type: "danger",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *LOGOUT
  $(".logoutBtn").click(async function () {
    try {
      const auth = new Auth();
      const res = await auth.logout();
      //redirect
      location.href = "";
    } catch (err) {
      console.log(err);
    }
  });

  // *REGISTER FORM
  $("#registerForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.register();
      if (res.status == 1) {
        $("#modalLoginAvatar").modal("hide");
        showModal({
          title: "User Registered",
          body: `Registration Successful. <br> <strong>Your User ID: ${res.data.userId}</strong>`,
          type: "success",
        });
        $(this).trigger("reset");
      } else {
        $("#modalLoginAvatar").modal("hide");
        showModal({
          title: "Registration Unsuccessful",
          body: res.status_message,
          type: "danger",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *UPDATE USER FORM
  $("#updateForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.update();
      if (res.status == 1) {
        showModal({
          title: "User Updated",
          body: `${res.status_message} <br> <strong>Your User ID: ${res.data.userId}</strong>`,
          type: "success",
          position: "right",
        });
      } else {
        showModal({
          title: "Updation Unsuccessful",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *UPDATE USER FORM BY ADMIN
  $("#updateFormByAdmin").submit(async function (e) {
    e.preventDefault();
    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.update();
      if (res.status == 1) {
        showModal({
          title: "User Updated",
          body: `${res.status_message} <br> <strong>Your User ID: ${res.data.userId}</strong>`,
          type: "success",
          position: "right",
        });
      } else {
        showModal({
          title: "Updation Unsuccessful",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
      $("#elegantModalForm").modal("hide");
    } catch (err) {
      console.log(err);
    }
  });

  // *CHANGE PASSWORD FORM
  $("#changePasswordForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.changePassword();
      if (res.status == 1) {
        showModal({
          title: "Password Changed",
          body: `${res.status_message}`,
          type: "success",
          position: "right",
        });
        $(this).trigger("reset");
      } else {
        showModal({
          title: "Invalid Password",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *ADD DIRECT USER FORM
  $("#addDirectUserForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.addDirectUser();

      if (res.status == 1) {
        showModal({
          title: "Direct Added",
          body: `Direct Added Successfully. <br> <strong>Direct User ID: ${res.data.userId}</strong>`,
          type: "success",
          position: "right",
        });
      } else {
        showModal({
          title: "Unsuccessful",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *ACTIVATE USER FORM
  $("#activateUserForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }

    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.activateUser();

      if (res.status == 1) {
        showModal({
          title: "User Activated",
          body: `User Activated Successfully. <br> <strong>User ID: ${res.data.userId}</strong>`,
          type: "success",
          position: "right",
        });
      } else {
        showModal({
          title: "Activation Unsuccessful",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *ACTIVATE TOPUP FORM
  $("#activateTopupForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }

    const data = processForm($(this));
    try {
      const user = new User(data);
      const res = await user.activateTopup();
      console.log(res);
      if (res.status == 1) {
        showModal({
          title: "Top up Done",
          body: `User Activated Successfully. <br> <strong>User ID: ${res.data.userId}</strong>`,
          type: "success",
          position: "right",
        });
      } else {
        showModal({
          title: "Top up Unsuccessful",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // * FETCH NAME BY USER ID
  $("#materialRegisterFormActivateUserId").blur(async function () {
    try {
      const user = new User({
        userId: $("#materialRegisterFormActivateUserId").val(),
      });
      const res = await user.getName();

      if (res.status == 1) {
        $("#materialRegisterFormName").val(res.name);
      } else {
        $("#materialRegisterFormName").val("");
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *BUY EPIN FORM
  $("#buyEpinsForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }

    // const data = processForm($(this));
    // try {
    //   console.log(data);
    //   amount = parseFloat(data.epinType).toFixed(2);

    //   const surl = "http://localhost/hockey98/response.php";
    //   const furl = "http://localhost/hockey98/response.php";
    //   easebuzzPayload = {
    //     txnid: data.txnId,
    //     amount: amount,
    //     firstname: data.firstName|| "Shubham",
    //     email: data.email,
    //     productinfo: "epin",
    //     phone: data.mobile,
    //     surl: surl,
    //     furl: furl,
    //     udf1: "a",
    //     udf2: "aa",
    //     udf3: "aaa",
    //     udf4: "aaaa",
    //     udf5: "aaaa",
    //     address1: "aaaaa",
    //     address2: "aaa",
    //     city: "aaa",
    //     country: "India",
    //     zipcode: "453001"
    //   };

    //   var formData = new FormData();
    //   const entries = Object.entries(easebuzzPayload);
    //   for (const [key, value] of entries) {
    //     formData.append(key, value);
    //   }

    //   $.ajax({
    //     url: "http://localhost/hockey98/easebuzz.php?api_name=initiate_payment",
    //     type: "POST",
    //     data: formData,
    //     contentType: false,
    //     processData: false,

    //     success: function(response) {
    //       console.log(response);
    //     },
    //     complete: function(response) {
    //       console.log(response);

    //     }
    //   })

    //   // const epin = new Epin(data);
    //   // const res = await epin.buyEpin();

    //   // if (res.status == 1) {
    //   //   showModal({
    //   //     title: "E-Pin Creditred",
    //   //     body: `E-Pins Credirect Successfully. <br> <strong>E-Pin IDs: ${res.data.join(
    //   //       ", "
    //   //     )}</strong>`,
    //   //     type: "success",
    //   //     position: "right",
    //   //   });
    //   //   $(this).trigger("reset");
    //   // } else {
    //   //   showModal({
    //   //     title: "Error",
    //   //     body: res.status_message,
    //   //     type: "danger",
    //   //     position: "right",
    //   //   });
    //   // }
    // } catch (err) {
    //   console.log(err);
    // }
  });

  // *BUY TOP UP FORM
  $("#buyTopupForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }

    const data = processForm($(this));
    try {
      const epin = new Epin(data);
      const res = await epin.buyEpin();

      if (res.status == 1) {
        showModal({
          title: "Top Up Creditec",
          body: `Top Credirect Successfully. <br> <strong>Top Up ID: ${res.data.join(
            ", "
          )}</strong>`,
          type: "success",
          position: "right",
        });
        $(this).trigger("reset");
      } else {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });
  
  // *TRANSFER EPIN FORM
  $("#transferEpinForm").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }

    const data = processForm($(this));
    try {
      const epin = new Epin(data);
      const res = await epin.transferEpins();
      if (res.status == 1) {
        showModal({
          title: "E-Pin Transfered",
          body: `${res.status_message}`,
          type: "success",
          position: "right",
        });
        $(this).trigger("reset");
      } else {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *TOGGLE WITHDRAW OPTION
  $("#toggleWithdraw").change(async function (e) {
    var status = e.target.checked?1:0;
    data = {
      status: status
    }
    try {
      const admin = new Admin(data);
      const res = await admin.toggleWithdraw();
      if (res.status == 1) {
        showModal({
          title: "Success",
          body: `${res.status_message}`,
          type: "success",
          position: "right",
        });
      } else if(status == 0) {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // Change denominations
  $("[name=denominations]").change(async function(e){
    const checkboxes = $("[name=denominations]");
    const actives = checkboxes.toArray().filter(c => c.checked==true);
    const results = actives.map(a => a.value);
    if(results.length < 1) {
      return alert("Atleast one denomination is required");
    }
    data = {
      denominations: results.join()
    }
    try {
      const admin = new Admin(data);
      const res = await admin.changeDenominations();
      console.log(res);
      if (res.status == 1) {
        console.log(res.status);
        showModal({
          title: "Success",
          body: `${res.status_message}`,
          type: "success",
          position: "right",
        });
      } else if(status == 0) {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  })

  // *TOGGLE WITHDRAW OPTION
  $(".assignCourier").submit(async function (e) {
    e.preventDefault();

    const data = processForm($(this));
    try {
      const admin = new Admin(data);
      const res = await admin.assignCourier();
      console.log(res);
      if (res.status == 1) {
        console.log(res.status);
        
        showModal({
          title: "Success",
          body: `${res.status_message}`,
          type: "success",
          position: "right",
        });
      } else if(status == 0) {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });

  // *ACTIVATE ID FORM
  $("#activate_account_form").submit(async function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
      return;
    }
    mobile = $("[name=mobile_number]").val();
    const data = processForm($(this));
    console.log(mobile, data.clientCode)
    if(data.clientCode != mobile) {
      showModal({
        title: "Error",
        body: "Please provide your correct mobile number.",
        type: "danger",
        position: "right",
      });
      return;
    }
    try {
      var user = new User(data);
      var res = await user.activateUserByClientCode();
      if (res.status == 1) {
        showModal({
          title: "Activated",
          body: res.status_message,
          type: "success",
          position: "right",
        });
        $(this).trigger("reset");
        location.reload();
      } else {
        showModal({
          title: "Error",
          body: res.status_message,
          type: "danger",
          position: "right",
        });
      }
    } catch (err) {
      console.log(err);
    }
  });
});
