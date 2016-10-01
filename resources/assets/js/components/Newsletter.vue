<template>
    <div class="newsletter-subscribe">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">Subscribe for our Newsletter</h2>
                <p class="text-center">Get latest update from our company. </p>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <input class="form-control" type="email" v-model="email" placeholder="Your Email" />
                </div>
                <div class="form-group">
                    <a class="btn btn-primary" v-on:click="subscribe">Subscribe </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    .newsletter-subscribe {
        color:#313437;
        background-color:#fff;
        padding:50px 0;
    }
    .newsletter-subscribe .intro {
        font-size:16px;
        max-width:500px;
        margin:0 auto 25px;
    }
    .newsletter-subscribe h2 {
        font-size:24px;
        font-weight:bold;
        margin-bottom:25px;
        line-height:1.5;
        padding-top:0;
        margin-top:0;
        color:inherit;
    }
    .newsletter-subscribe .intro p {
        margin-bottom:35px;
    }
    .newsletter-subscribe form {
        text-align:center;
    }
    .newsletter-subscribe form .form-control {
        background:#eff1f4;
        border:none;
        border-radius:3px;
        box-shadow:none;
        outline:none;
        color:inherit;
        text-indent:9px;
        height:45px;
        margin-right:10px;
        min-width:250px;
    }
    .newsletter-subscribe form .btn:active {
        transform:translateY(1px);
    }
    .newsletter-subscribe form .btn:hover {
        opacity:1;
    }
</style>

<script>
    export default {
        ready() {
            console.log('Newsletter Component ready.')
        },
        data() {
            return {
                email : ''
            }
        },
        methods : {
            subscribe : function() {
                console.log(this.email);
                this.$http.post('/api/subscribe', {'email':this.email}).then((response) => {
                    console.log(response);
                    alert(response.data.message);
                }, (response) => {
                    console.log(response);
                    alert(response.data.email[0]);
                });
            }
        }
    }
</script>
