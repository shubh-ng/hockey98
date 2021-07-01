class Auth {
  constructor(login) {
    this.url = {
      auth: baseUrl + "/auth",
      logout: baseUrl + "/logout",
    };
    this.formData = new FormData();
    this.initFormData(login);
  }

  initFormData(login = {}) {
    const entries = Object.entries(login);
    for (const [key, value] of entries) {
      this.formData.append(key, value);
    }
  }

  login() {
    this.formData.append("action", "auth");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.auth,
        data: this.formData,
        contentType: false,
        processData: false,
        success: function (res) {
          resolve(res);
        },
        complete: function (res) {
          console.log("Complete");
        },
      });
    });
  }

 adminLogin() {
    this.formData.append("action", "adminAuth");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.auth,
        data: this.formData,
        contentType: false,
        processData: false,
        success: function (res) {
          resolve(res);
        },
        complete: function (res) {
          console.log("Complete");
        },
      });
    });
  }  

  logout() {
    this.formData.append("action", "logout");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.logout,
        data: this.formData,
        contentType: false,
        processData: false,
        success: function (res) {
          resolve(res);
        },
        complete: function (res) {
          console.log("Complete");
        },
      });
    });
  }
}
