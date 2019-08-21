<div class="container">
			<div class="row">
				<div class="col-xs-14 col-sm-7 col-lg-7">
					<div class='image'>
						<img src="logo.png" class="img-responsive"/>
					</div>
				</div>
				<div class="col-xs-10 col-sm-5 col-lg-5">
					<div class="intro">
						<p class="text-center">
							Please enter your name
						</p>
						<?php if(empty($_SESSION['name'])){?>
						<form class="form-signin" method="post" id='signin' name="signin" action="&q=1">
							<div class="form-group">
								<input type="text" id='name' name='name' class="form-control" placeholder="Enter your Name"/>
								<span class="help-block"></span>
							</div>
							<div class="form-group">
							    <select class="form-control" name="category" id="category">
							        <option value="">Choose your category</option>
                                  <option value="1">Sports</option>
                                  <option value="2">HTML</option>
                                  <option value="3">PHP</option>
                                  <option value="4">CSS</option>                                
                                </select>
                                <span class="help-block"></span>
							</div>

							<br>
							<button class="btn btn-success btn-block" type="submit">
								Kiss Me!!!
							</button>
						</form>

						<?php }else{?>
						    <form class="form-signin" method="post" id='signin' name="signin" action="&q=1">
                            <div class="form-group">
                                <select class="form-control" name="category" id="category">
                                    <option value="">Choose your category</option>
                                  <option value="1">Sports</option>
                                  <option value="2">HTML</option>
                                  <option value="3">PHP</option>
                                  <option value="4">CSS</option>                                
                                </select>
                                <span class="help-block"></span>
                            </div>

                            <br>
                            <button class="btn btn-success btn-block" type="submit">
                                Kiss Me!!!
                            </button>
                        </form>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		{literal}
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-1.10.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/jquery.validate.min.js"></script>

		<script>
			$(document).ready(function() {
				$("#signin").validate({
					submitHandler : function() {
					    console.log(form.valid());
						if (form.valid()) {
						    alert("sf");
							return true;
						} else {
							return false;
						}

					},
					rules : {
						name : {
							required : true,
							minlength : 3,
							remote : {
								url : "check_name.php",
								type : "post",
								data : {
									username : function() {
										return $("#name").val();
									}
								}
							}
						},
						category:{
						    required : true
						}
					},
					messages : {
						name : {
							required : "Please enter your name",
							remote : "Name is already taken, Please choose some other name"
						},
						category:{
                            required : "Please choose your category to start Quiz"
                        }
					},
					errorPlacement : function(error, element) {
						$(element).closest('.form-group').find('.help-block').html(error.html());
					},
					highlight : function(element) {
						$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
					},
					success : function(element, lab) {
						var messages = new Array("That's Great!", "Looks good!", "You got it!", "Great Job!", "Smart!", "That's it!");
						var num = Math.floor(Math.random() * 6);
						$(lab).closest('.form-group').find('.help-block').text(messages[num]);
						$(lab).addClass('valid').closest('.form-group').removeClass('has-error').addClass('has-success');
					}
				});
			});
		</script>
		
		{/literal}
