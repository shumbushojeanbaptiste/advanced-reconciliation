var settings = {
  "url": "https://xode.rw/api/clients/build/fee",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "pay_ref_no": "205106479",
    "slip_no": "2345678",
    "pay_amount": "7000",
    "trans_code": "40577890"
  }),
};

$.ajax(settings).done(function (response) {
  console.log(response);
});