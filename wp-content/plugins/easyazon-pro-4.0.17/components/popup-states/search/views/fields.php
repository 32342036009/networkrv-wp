<tr data-bind="visible: indicesExist">
	<th scope="row"><label for="easyazon-search-search-index"><?php _e('Search Index'); ?></label></th>
	<td>
		<select id="easyazon-search-search-index" data-bind="options: indices, optionsText: 'name', value: index"></select>
	</td>
</tr>

<tr data-bind="visible: priceMinAvailable">
	<th scope="row"><label for="easyazon-search-minimum-price"><?php _e('Minimum Price'); ?></label></th>
	<td>
		<input type="text" class="code regular-text" id="easyazon-search-minimum-price" data-bind="value: priceMin" />
		<p class="description"><?php _e('This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)'); ?></p>
	</td>
</tr>

<tr data-bind="visible: priceMaxAvailable">
	<th scope="row"><label for="easyazon-search-maximum-price"><?php _e('Maximum Price'); ?></label></th>
	<td>
		<input type="text" class="code regular-text" id="easyazon-search-maximum-price" data-bind="value: priceMax" />
		<p class="description"><?php _e('This value will be interpreted in the context of the locale you are searching within (e.g. $ in the United States)'); ?></p>
	</td>
</tr>

<tr data-bind="visible: sortsExist">
	<th scope="row"><label for="easyazon-search-search-index"><?php _e('Sort'); ?></label></th>
	<td>
		<select id="easyazon-search-search-index" data-bind="options: sorts, optionsText: 'name', value: sort"></select>
	</td>
</tr>