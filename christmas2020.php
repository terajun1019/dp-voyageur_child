	<div class="container">
		<form id="ch22-search-form">
			<div>
				<?php
					$tags = get_terms('product_tag');
					if ($tags) : ?>
						<h3>お探しのサイズは？</h3>
						<label for ="check_all_tag"><input type="checkbox" id="check_all_tag" name="check_all_tag">すべて</label>
						<div id="checks_tag">
						<?php
							$checkboxes = '';
							foreach ($tags as $tag) {
								// サイズタグのみ抽出
								if(strstr($tag->slug, 'size_')){
									$checkboxes .= '<label for="tag-' . $tag->term_id . '">' . 
										'<input type="checkbox" name="tag[]" value="' . $tag->slug . '" id="tag-' . $tag->term_id . '" class="check_tag" />' .
										$tag->name . '</label>' . ' ';
								}
							}
							print $checkboxes;?>
						</div>
					<?php endif;								
					$blands = get_terms('product_cat', 'parent=66'); // &number=9');
					if ($blands) : ?>
						<h3>お好みのブランドは？</h3>
						<label for ="check_all_bland"><input type="checkbox" id="check_all_bland" name="check_all_bland">すべて</label>
						<div id="checks_bland">
						<?php
						$checkboxes = '';
						foreach ($blands as $bland) {
							// サイズタグのみ抽出
							// if(strstr($bland->slug, 'size_')){
								$checkboxes .= '<input type="checkbox" name="bland[]" value="' . $bland->slug . '" id="bland-' . $bland->term_id . '" class="check_bland" />' .
									'<label for="bland-' . $bland->term_id . '">' . $bland->name . '</label>' . ' ';
							// }
						}
						$checkboxes .= '<input type="checkbox" name="" value="" id="bland-any" />' .
							'<label for="bland-any">何でもＯＫ</label>';
						print $checkboxes;?>
						</div>
					<?php endif; ?>
				<button class="btn btn-danger btn-lg btn-block" id="ch22-search-button" type="button">この条件で検索！</button>
			</div>

		</form>
	</div>
	<hr>

	<div id="ch22-search-result">
		<!-- ここが書き変わる！ -->
	</div>


<script type="text/javascript">
	window.addEventListener("load", function() {
		document.getElementById("ch22-search-button").addEventListener("click",
			function() {
				var formDatas = document.getElementById("ch22-search-form");
				var resultDatas = new FormData(formDatas);

				var XHR = new XMLHttpRequest();
				XHR.open("POST", "<?php echo home_url('christmas2020-searchresult'); ?>", true);
				XHR.send(resultDatas);
				XHR.onreadystatechange = function() {
					if (XHR.readyState == 4 && XHR.status == 200) {
						document.getElementById("ch22-search-result").innerHTML = XHR.responseText;
					}
				};
			}, false);
	}, false);

	$(function(){
		$('#check_all_tag').on('change', function() {
			// 「選択肢」のチェック状態を切替える
			$('.check_tag').prop('checked', $(this).is(':checked'));
		});
		$('#check_all_bland').on('change', function() {
			// 「選択肢」のチェック状態を切替える
			$('.check_bland').prop('checked', $(this).is(':checked'));
		});
	});
	
</script>

<?php