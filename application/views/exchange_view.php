<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Currency exchange</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <?php echo link_tag('assets/css/currency.css'); ?>
</head>
<body>
    <div id="app" class="container">
        <div id="body">
            <div class="alert alert-danger" role="alert" v-if="errors">
                {{ errors }}
            </div>
            <div class="calculator row" v-else>
                <div class="col-4">
                    <h3>Currency rate calculator</h3>
                    <div class="d-flex justify-content-between">
                        <input class="currency-input" id="amount_from" @keyup="calcInput_1" :value="calc1">
                        <select class="form-select" id="from" name="from" @change="calcInput_1">
                            <option v-for="(rate, index) in rates" :value="index" :selected="index === 'EUR'">{{ index }}</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <input class="currency-input" id="amount_to" @keyup="calcInput_2" :value="calc2">
                        <select class="form-select" id="to" name="to" @change="calcInput_2">
                            <option v-for="(rate, index) in rates" :value="index">{{ index }}</option>
                        </select>
                    </div>
                    <p>{{ calcRate }}</p>
                </div>
                <div class="col-6">
                    <h3>Currency rates {{ updated }}</h3>
                    <button type="button" class="btn btn-outline-primary btn-sm text-right" v-on:click="getRates">UpdateRates</button>
                    <br>
                    <br>
                    <div class="tableFixHead">
                        <table class="table table-hover table-bordered table-responsive table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Currency</th>
                                <th scope="col">Rate (Based on {{ rateCurrency }}</b>)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(rate, index) in rates">
                                <td>{{ index }}</td>
                                <td>{{ rate }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.5/vue.min.js"></script>
<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            calc1: "",
            calc2: "",
            calcRate: "",
            firstInputSelected: true,
            rates: [],
            rateCurrency: 'EUR',
            updated: "",
            errors: ""
        },
        created () {
            fetch('http://localhost:7700/index.php/exchange/getCurrentRates', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
                .then((response) => response.json())
                .then((result) => {
                    this.rates = result.value;
                    this.rateCurrency = result.rate;
                    this.updated = result.updated;
                    this.errors = result.errors;
                })
        },
        methods: {
            getRates: function () {
                fetch('http://localhost:7700/index.php/exchange/getCurrentRates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                    .then((response) => response.json())
                    .then((result) => {
                        this.rates = result.value;
                        this.rateCurrency = result.rate;
                        this.updated = result.updated;
                    })
            },
            isNumeric: function (n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            },
            calcInput_1: function () {
                let amount = document.getElementById("amount_from").value;
                let fromCurrency = document.getElementById("from").value;
                let toCurrency = document.getElementById("to").value;
                this.firstInputSelected = true;
                if(this.isNumeric(amount)) {
                    this.convertCurrency(fromCurrency, toCurrency, amount);
                }
            },
            calcInput_2: function () {
                let amount = document.getElementById("amount_to").value;
                let fromCurrency = document.getElementById("to").value;
                let toCurrency = document.getElementById("from").value;
                this.firstInputSelected = false;
                if(this.isNumeric(amount)) {
                    this.convertCurrency(fromCurrency, toCurrency, amount);
                }
            },
            convertCurrency: function (fromCurrency, toCurrency, amount) {
                this.loading = true;
                fetch('http://localhost:7700/index.php/exchange/convert?amount='+amount+'&from='+fromCurrency+'&to='+toCurrency, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    // body: JSON.stringify({
                    //     fromCurrency: fromCurrency,
                    //     toCurrency: toCurrency,
                    //     amount: amount
                    // })
                })
                .then((response) => response.json())
                .then((result) => {
                    this.calcRate = 'Rate: ' + result.rate;
                    if(this.firstInputSelected){
                        this.calc2 = result.value;
                        this.calc1 = document.getElementById("amount_from").value
                    } else {
                        this.calc1 = result.value;
                        this.calc2 = document.getElementById("amount_to").value
                    }
                })
            }
        }
    });
</script>
</html>