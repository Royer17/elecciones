var xlf = document.getElementById('file');
var X = XLSX;
var index_imports = 0;
var data_imports=null;
var cant_imports=0;

var out;

function process_wb(wb){
    output = to_csv(wb);
    output = output.split('\n');
    out = output;
    var cant = calc_cant(output);
    data_imports=clear_data(output); //Crear otro registro pero sin filas en blanco
    console.log(data_imports);
    console.log(cant);
}

function calc_cant(data){
    var cant=0;
    if(data!=""){ // los registros a partir de la fila 2
        for(var i=1;i<data.length;i++){
            if(data[i].split('=?')[0]!=""){
                cant++;
            }
        }
    }
    return cant;
}

function clear_data(data){
    var cant=0;
    var j=0;
    var aux = new Array();
    if(data!=""){ // los registros a partir de la fila 3
        for(var i=1;i<data.length;i++){
            if(data[i].split('=?')[0]!=""){
                aux[j]=data[i];
                cant++;
                j++;
            }
        }
    }
    return aux;
}

function to_csv(workbook) {
    var result = [];
    workbook.SheetNames.forEach(function(sheetName) {
        var csv = X.utils.sheet_to_csv(workbook.Sheets[sheetName], null, "=?");
        //console.log(csv);
        if(csv.length > 0){
            /*result.push("SHEET: " + sheetName);
            result.push("");*/
            result.push(csv);
        }
    });
    return result.join("\n");
}

function fixdata(data) {
    var o = "", l = 0, w = 10240;
    for(; l<data.byteLength/w; ++l) o+=String.fromCharCode.apply(null,new Uint8Array(data.slice(l*w,l*w+w)));
    o+=String.fromCharCode.apply(null, new Uint8Array(data.slice(l*w)));
    return o;
}

function handFile(e){
    var files = e.target.files;
    var f = files[0];
    {
        var reader = new FileReader();
        var name = f.name;

        reader.onload = function(e) {
            //if(typeof console !== 'undefined') console.log("onload", new Date(), rABS, use_worker);
            var data = e.target.result;
            //console.log("-----------------------------------------------------");
            //console.log(data);
            var arr = fixdata(data);
            //console.log("--------------------");
            //console.log(arr);
            var wb;
            wb = X.read(btoa(arr), {type: 'base64'});
            //console.log(wb);
            process_wb(wb);
        };
        reader.readAsArrayBuffer(f);
    }

    //var files = e.
}

xlf.addEventListener('change',handFile,false);

$(`#import`).on('click', function(e){
	e.preventDefault();

	  let code = new Array();
	  let regimen = new Array();
	  let name = new Array();
	  let description = new Array();
	  let price = new Array();
	  let promotion_available = new Array();
	  let price_promotion = new Array();
	  let stock = new Array();
	  let category = new Array();
	  let subcategory = new Array();
	  let outstanding = new Array();
	  let published = new Array();

	  let splitted;
	  data_imports.forEach((value, index) => {

	    splitted = value.split(`=?`);

	    code[index] = splitted[1];
	    //regimen[index] = splitted[2];
	    name[index] = splitted[2];
	    description[index] = splitted[3];
	    price[index] = splitted[4];
	    //promotion_available[index] = splitted[6];
	    //price_promotion[index] = splitted[7];
	    stock[index] = splitted[5];
	    category[index] = splitted[6];
	    //subcategory[index] = splitted[10];
	    //outstanding[index] = splitted[11];
	    //published[index] = splitted[12];
	  });

		// fetch('/products-import', {
		//    method: 'POST',
		//    headers: {
		//    	"X-CSRF-TOKEN": $('#token').val(),
		//    },
		//    body: JSON.stringify({	
		// 		    code :code,
		// 		    regimen : regimen,
		// 		    name : name,
		// 		    description : description,
		// 		    price : price,
		// 		    promotion_available : promotion_available,
		// 		    price_promotion : price_promotion,
		// 		    stock : stock,
		// 		    category : category,
		// 		    subcategory : subcategory,
		// 		    outstanding : outstanding,
		// 		    published : published,
		// 		  })
		// })
		// .then(function(response) {
		//    if(response.ok) {
		//        return response.text()
		//    } else {
		//        throw "Error en la llamada Ajax";
		//    }
		// })
		// .then(function(texto) {
		//    console.log(texto);
		// })
		// .catch(function(err) {
		//    console.log(err);
		// });


	  axios.post(`/products-import`, {
	    code :code,
	    //regimen : regimen,
	    name : name,
	    description : description,
	    price : price,
	    //promotion_available : promotion_available,
	    //price_promotion : price_promotion,
	    stock : stock,
	    category : category,
	    //subcategory : subcategory,
	    //outstanding : outstanding,
	    //published : published,
	  })
	  .then((response) => {
	  	console.log(response.data);
	    location.reload();
	  });
});