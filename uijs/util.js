const processForm = (ref) => {
  let data = ref.serializeArray();
  let processedData = {};
  data.forEach((d) => {
    processedData[d.name] = d.value;
  });
  return processedData;
};

const showModal = ({ title, body, type, position }) => {
  $(`#${type}ModalTitle`).html(title);
  $(`#${type}ModalBody`).html(body);
  $(`#${type}Modal`).modal("show");
  if (position) {
    $(`#${type}Modal`).addClass(position);
    $(`#${type}Modal .modal-dialog`).addClass(
      `modal-side modal-top-${position}`
    );
  }
};

const hideModal = ({ type }) => {
  $(`#${type}ModalTitle`).html("....");
  $(`#${type}ModalBody`).html("....");
  $(`#${type}Modal`).modal("hide");
};
