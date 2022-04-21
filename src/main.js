
window.addToCart = function(itemid) {
  const item = document.querySelector("#qty-" + itemid);
  if (item.value != 0) {
    fetch('src/addtocart.php', {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
      },
      body: `itemid=${itemid}&quantity=${item.value}`,
    });
  }
}

window.applyCoupon = function() {
  const coupon = document.querySelector("#couponcode");

  fetch('../src/applyCoupon.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: `couponcode=${coupon.value}`,
  }).then(() => coupon.value = "").then(() => window.location.reload());
}