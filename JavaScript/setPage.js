function hidePrice(id, variant){
    var checkBox = document.getElementById("INV_" + id);
    var marketPrice = document.getElementById("MP_" + id);

    var size = document.getElementById("size");
    var price = document.getElementById("setPrice");
    var mSize = document.getElementById("mSize");
    var mPrice = document.getElementById("mSetPrice");

    if(checkBox.checked){
        marketPrice.style.display = "none";

        if(variant === 1){
            size.textContent = parseInt(size.textContent) - 1;
            price.textContent = parseFloat(price.textContent.replace(/,/g, '')) - parseFloat(marketPrice.textContent.substring(1).replace(/,/g, ''));
        }
        mSize.textContent = parseInt(mSize.textContent) - 1;
        mPrice.textContent = parseFloat(mPrice.textContent.replace(/,/g, '')) - parseFloat(marketPrice.textContent.substring(1).replace(/,/g, ''));
    }
    else{
        marketPrice.style.display = "block";

        if(variant === 1){
            size.textContent = parseInt(size.textContent) + 1;
            price.textContent = parseFloat(price.textContent.replace(/,/g, '')) + parseFloat(marketPrice.textContent.substring(1).replace(/,/g, ''));
        }
        mSize.textContent = parseInt(mSize.textContent) - 1;
        mPrice.textContent = parseFloat(mPrice.textContent.replace(/,/g, '')) + parseFloat(marketPrice.textContent.substring(1).replace(/,/g, ''));
    }
}