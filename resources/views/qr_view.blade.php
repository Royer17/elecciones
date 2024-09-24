<!-- <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Testeando</title>
    <meta name="description" content="test" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  </head>
  <body>
    <button onclick="compartirQR()">Compartir QR</button>
    <span> ------- este boton solo funciona en paginas HTTPS</span>
    <div id="testdiv"></div>
    <br />
    <br />
    <b>Generar QR:</b>
    <input readonly id="qr" name="qr" type="text" />
    <button onclick="compatirTexto()">Compartir</button>
    <br />
    <b>Ingresa algun texto: </b>
    <input
      oninput="actualizarQR()"
      id="sampletext"
      name="sampletext"
      type="text"
    />
    <script>
      const qrInput = document.getElementById('qr'),
        sampletext = document.getElementById('sampletext')
      function compartirQR () {
        document.getElementById('testdiv').innerText = 'Click ' + Date.now()
        if (navigator.share) {
          navigator
            .share({
              text: qr.value
            })
            .then(() => console.log('Successful share'))
            .catch(error => console.log('Error sharing', error))
        }
      }
      function compatirTexto () {
        console.log('Compartiendo texto por seleccion')
        qrInput.focus()
        qrInput.select()
      }
      function actualizarQR () {
        console.log('Actualizando el input text del QR')
        qrInput.value = sampletext.value
      }
    </script>
  </body>
</html>
 -->