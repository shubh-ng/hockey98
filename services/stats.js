class Stats {
  constructor(stats) {
    this.url = {
      getAllUsersStats: baseUrl + "/getAllUsersStats",
    };
    this.formData = new FormData();
    this.initFormData(stats);
  }

  initFormData(data = {}) {
    const entries = Object.entries(data);
    for (const [key, value] of entries) {
      this.formData.append(key, value);
    }
  }

  getAllUsersStats() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: this.url.getAllUsersStats,
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
