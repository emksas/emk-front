<div id="global-loader" class="loader-overlay" style="display: none;">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <h3 id="loader-title">Cargando...</h3>
        <p id="loader-message">Por favor espere un momento.</p>
    </div>
</div>

<style>
.loader-overlay{
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,.85);
    backdrop-filter: blur(2px);
    z-index: 99999;

    display: flex;
    justify-content: center;
    align-items: center;
}

.loader-content{
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,.15);
    text-align: center;
    min-width: 350px;
}

.loader-spinner{
    width:70px;
    height:70px;
    margin:auto;

    border:7px solid #e5e5e5;
    border-top:7px solid #0d6efd;
    border-radius:50%;

    animation:loader-spin .8s linear infinite;
}

.loader-content h3{
    margin-top:20px;
    margin-bottom:10px;
    font-size:22px;
}

.loader-content p{
    color:#666;
    margin:0;
}

@keyframes loader-spin{
    from{
        transform:rotate(0deg);
    }
    to{
        transform:rotate(360deg);
    }
}
</style>

<script>

function showLoader(
    title = 'Cargando...',
    message = 'Por favor espere un momento.'
){

    document.getElementById('loader-title').innerText = title;
    document.getElementById('loader-message').innerText = message;

    document.getElementById('global-loader').style.display='flex';

}

function hideLoader(){

    document.getElementById('global-loader').style.display='none';

}

</script>