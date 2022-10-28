<script>
$.getJSON("http://localhost/latihan-chartjs/data-produk.php", function(data) {

    var isi_labels = [];
    var isi_data = [];

    $(data).each(function(i) {
        isi_labels.push(data[i].NamaProduk);
        isi_data.push(data[i].Jml);
    });
    console.log(isi_labels);
    console.log(isi_data);
});
</script>