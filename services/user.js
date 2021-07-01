class User {
  constructor(user) {
    this.url = {
      register: baseUrl + "/register",
      addUser: baseUrl + "/addDirectUser",
      activateUser: baseUrl + "/activateUser",
      getName: baseUrl + "/getName",
      getDirectUsers: baseUrl + "/getDirectUsers",
      getUserInfo: baseUrl + "/getUserInfo",
      updateUser: baseUrl + "/updateUser",
      changePassword: baseUrl + "/changePassword",
      activateTopup: baseUrl + "/activateTopup",
      getUsers: baseUrl + "/getUsers",
      deleteUser: baseUrl + "/deleteUser",
      activateUserByClientCode: baseUrl + "/activateUserByClientCode",
      aliceBlueAPI: "https://app.aliceblueonline.com/services/partnerekyc.asmx/GetLeadDetails?Remname=WMUM69&Authcode=QVNMUEIzNTI0RA==&mobileNo=",
    };
    this.formData = new FormData();
    this.initFormData(user);
  }

  initFormData(data = {}) {
    const entries = Object.entries(data);
    for (const [key, value] of entries) {
      this.formData.append(key, value);
    }
  }

  register() {
    this.formData.append("action", "register");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.register,
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

  update() {
    this.formData.append("action", "updateUser");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.updateUser,
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

  changePassword() {
    this.formData.append("action", "changePassword");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.changePassword,
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

  addDirectUser() {
    this.formData.append("action", "addUser");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.addUser,
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

  activateUser() {
    this.formData.append("action", "activateUser");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.activateUser,
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

  getName() {
    this.formData.append("action", "getName");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.getName,
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

  getUserInfo() {
    this.formData.append("action", "getUserInfo");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.getUserInfo,
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

  getUsers() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: this.url.getUsers + "?action=getUsers",
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

  activateTopup() {
    this.formData.append("action", "activateTopup");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.activateTopup,
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

  deleteUser() {
    this.formData.append("action", "deleteUser");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.deleteUser,
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

  activateUserByClientCode() {
    this.formData.append("action", "activateUserByClientCode");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.activateUserByClientCode,
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

  // Third party API
  getUserAliceBlueStatus() {
    const mobileNumber = this.formData.get("clientCode");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: this.url.aliceBlueAPI+mobileNumber,
        // dataType: 'jsonp',
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
