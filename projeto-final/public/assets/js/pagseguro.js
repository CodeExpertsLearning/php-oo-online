$("document").ready(function(){
    getAllPaymentMethods();
});

function getAllPaymentMethods() {
    PagSeguroDirectPayment.getPaymentMethods({
        amount: totalPurchase,
        success: function(response) {
            drawPaymentsMethodsView(response.paymentMethods);
        },
        error: function(response) {
            //console.log(response);
        },
        complete: function(response) {
            //console.log(response);
        }
    });
}

$(document).on('keyup', '#cardNumber', function () {
    getBrand();

    if ($('#cardNumber').val().length == 0) {
        $('#brand').empty();
    }
});

function getBrand() {
    let value = $('#cardNumber').val();

    if (value.length >= 6) {
        PagSeguroDirectPayment.getBrand({
            cardBin: value.substr(0, 6),
            success: function(response) {
                let brand = '<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/'+ response.brand.name + '.png">';
                $('#brand').html('Bandeira: ' + brand);
                $("#cardBrand").val(response.brand.name);
                getInstallments(totalPurchase);
            },
            error: function(err) {
                //console.log('error', err);
            },
            complete: function(response) {
                //console.log('complete', response);
            }
        });
    }
}

function getInstallments(amount) {
    let brand = $("#cardBrand").val();
    $("#installmentsArea").html("<h4>Carregando opções de parcelamento...</h4>");

    PagSeguroDirectPayment.getInstallments({
        amount: amount,
        maxInstallmentNoInterest: 3,
        brand: brand,
        success: function(s) {
            //console.log(s);
        },
        error: function(e) {
            //console.log(e);
        },
        complete: function(s) {
            //console.log(s);
            let installmentsDraw = drawInstallments(s.installments[brand]);

            $("#installmentsArea").html(installmentsDraw);
        }
    });
}

$(document).on('click', '#pay',function () {
    $(this).addClass('disabled');

    let method = $(this).attr('href');
    method = method.replace('#', '');

    if(method == 'CREDIT_CARD') {
        PagSeguroDirectPayment.createCardToken({
            cardNumber: $('#cardNumber').val(),
            brand: $('#cardBrand').val(),
            cvv: $('#cardCVV').val(),
            expirationMonth: $('#cardMonth').val(),
            expirationYear: $('#cardYear').val(),
            success: function (response) {
                //console.log('token generated', response.card.token);
                makePurchase(response.card.token, method);
            }
        });
    } else {
        makePurchase('', method);
    }

    return false;
});

function makePurchase(token, method) {
    $(".loading").show();

    let data = {
        method: method,
        hash: PagSeguroDirectPayment.getSenderHash()
    };

    if(method == 'CREDIT_CARD') {
        data.token = token;
        data.installments = $("#installmentsSelect").val();
    }

    $.ajax({
        type: "POST",
        url: mainUrl + 'payments/proccess',
        data: data,
        dataType: 'json',
        success: function (response) {
            $(".loading").hide();

            if(response.success) {
                $(".msg").html('<div class="alert alert-success">Compra efetuada com sucesso!</div>');
               window.location.href = mainUrl + 'payments/success';
            } else {
                $(".msg").html('<div class="alert alert-danger">Erro ao realizar transação!</div>');
            }
        }
    });
}
