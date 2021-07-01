class Admin {
    constructor(admin) {
      this.url = {
        toggleWithdraw: baseUrl + "/toggleWithdraw",
        assignCourier: baseUrl + "/assignCourier",
        getEpinTransfer: baseUrl + "/getEpinTransfer",
        changeDenominations: baseUrl + "/changeDenominations",
      };
      this.formData = new FormData();
      this.initFormData(admin);
    }
  
    initFormData(data = {}) {
      const entries = Object.entries(data);
      for (const [key, value] of entries) {
        this.formData.append(key, value);
      }
    }
  
    toggleWithdraw() {
      this.formData.append("action", "toggleWithdraw");
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: this.url.toggleWithdraw,
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

 
    assignCourier() {
      this.formData.append("action", "assignCourier");
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: this.url.assignCourier,
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
 
    getEpinTransferDetails() {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: this.url.getEpinTransfer,
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
 
    changeDenominations() {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: this.url.changeDenominations,
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
  