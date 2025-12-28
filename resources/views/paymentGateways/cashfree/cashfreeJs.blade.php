

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company['company_name']  }}</title>
    <link rel="icon" href="{{ $faviconLogo->faviconLogo }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/lab/lab.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
</head>
<body>

<div class="cashfree-logo py-14 px-4 w-full max-w-2xl mx-auto flex flex-col items-center justify-center">
    <a href="{{ route('home') }}" class="w-36 mb-8">
        <img class="w-full" src="{{ $logo->logo }}" alt="logo">
    </a>

</div>

<script type="application/javascript">
    let data       = JSON.parse(localStorage.getItem('vuex'));
    const url      = '<?=URL::to('/') . "/table-order/"?>';
    const order_id = '<?=$order?->id?>';
    if (data.tableCart.paymentMethod) {
        document.getElementById('home-route').setAttribute('href', url + data.tableCart.table.slug + '/' + order_id);
    }
</script>

<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
    const paymentSessionId = '<?= $paymentSessionId ?>';
    const cashfreePayLink = '<?= $cashfreePayLink ?>';
    const cashfreeCancelLink = '<?= $cashfreeCancelLink ?>';
    const mode = '<?= $mode ?>';

    const cashfree = Cashfree({
        mode: mode
    });
    let checkoutOptions = {
        paymentSessionId: paymentSessionId,
        redirectTarget: "_modal"
    }
    cashfree.checkout(checkoutOptions).then((result) => {
        console.log(result);
        if (result.error) {
            window.location.href = cashfreeCancelLink;
        }
        if (result.paymentDetails) {
            window.location.href = cashfreePayLink;
        }
    });
</script>

</body>
</html>
