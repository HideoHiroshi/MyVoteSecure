<script>
function ubahsaiz(gandaan) {
    var saiz = document.getElementById("saiz");
    saiz.style.fontSize = gandaan === 2 ? "1em" : 
    (parseFloat(saiz.style.fontSize || 1) + (gandaan * 0.2)) + "em";
}
</script>

| ubah saiz tulisan | 
<input type='button' value='reset' onclick="ubahsaiz(2)" />
<input type='button' value='&nbsp; + &nbsp;' onclick="ubahsaiz(1)" />
<input type='button' value='&nbsp; - &nbsp;' onclick="ubahsaiz(-1)" />

| <button onclick="window.print()">Cetak</button>