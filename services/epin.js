class Epin {
  constructor(epin) {
    this.url = {
      buyepin: baseUrl + "/buyepin",
      getepins: baseUrl + "/getEpins",
      transferEpin: baseUrl + "/transferEpin",
    };
    this.formData = new FormData();
    this.initFormData(epin);
  }

  initFormData(data = {}) {
    const entries = Object.entries(data);
    for (const [key, value] of entries) {
      this.formData.append(key, value);
    }
  }

  buyEpin() {
    this.formData.append("action", "buyepin");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.buyepin,
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

  getEpins() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `${this.url.getepins}?action=getEpins&cost=${this.formData.get(
          "cost"
        )}`,
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

  transferEpins() {
    this.formData.append("action", "transferEpin");
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: this.url.transferEpin,
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
