	
	$(document).on('click', '.add_to_shopping_cart', function(e) {
		e.preventDefault();
		let _product_id = $(this)[0].dataset.index, _quantity;

		_quantity = $(this).prev().find('.product_quantity').val();

		if (!parseInt(_quantity)) {
			return;
		}

		addingProduct(_product_id, parseInt(_quantity));
		successAddedCart();
		update_total([$(`.cart_total`)]);
        //update_sticky_cart();
	});

	function addingProduct(product_id, quantity = 1) {
		let _cart = {};
		if (localStorage.getItem("cart") == null) {
	 		_cart[product_id] = quantity;
		} else {
			_cart = JSON.parse(localStorage.getItem("cart"));
			if (_cart[product_id]) {
				let _value = parseInt(_cart[product_id])+parseInt(quantity);
				_cart[product_id] = _value;
			} else {
				_cart[product_id] = quantity;
			}
		}
		localStorage.setItem(`cart`, JSON.stringify(_cart));
	}

	function successAddedCart(){
		Swal.fire({
	      position: 'center',
	     // icon: 'success',
	      title: '¡Se ha agregado a su carrito!',
	      showConfirmButton: false,
	      timer: 1500,
	      /*showClass: {
	        popup: 'animated fadeInDown faster'
	      },*/
	       imageUrl: 'https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/dab60938212491.5968c68fa9113.gif',
	      imageAlt: 'Custom image',
	      imageWidth: 250,
	       // imageHeight: 200,
	    })
	}

	function updateCartQuantity(){
		let _cart = localStorage.getItem('cart');
		
		$(`.cart_quantity`).html(0);
		//document.querySelector(`#cart_quantity`).innerHTML = 0;
		
		if (_cart != undefined) {
			let _cart_parsed = JSON.parse(_cart), _sum = 0;
			_sum = Object.values(_cart_parsed).reduce((a, b) => parseInt(a) + parseInt(b), 0); 
			$(`.cart_quantity`).html(_sum);
		}
	}

	function update_total(elements){

		if (!elements) return;
		axios.get(`/cart-total?ids=${localStorage.getItem("cart")}`)
		    .then((response) => {
		    	elements.forEach((element) => {
		        	element.html(`S/${response.data.toFixed(2)}`);
		    	});
		    	updateCartQuantity();
		    });
    }
