<?php
	
class keywords_list
{
	public function show_suggestions( $keyword_array, $wpst_sortfield, $wpst_sorttype, $credits_remaining )
	{
		if( ! is_array ($keyword_array) ) die();

		$array_field_sorted = array(); $array_field_sorted['volume'] = ''; $array_field_sorted['cpc'] = ''; $array_field_sorted['profit'] = '';

		$array_field_sorted[$wpst_sortfield] = ' wpst-sorted-'.$wpst_sorttype;

		$html = '
		<table id="wpst-table" class="widefat">
		<thead>
		<tr>
			<th scope="col" class="keyword">
				'.__('Keyword', _PLUGIN_NAME_).' <a href="#" class="button" title="'.__('Keyword: key phrase.', _PLUGIN_NAME_).'">?</a>
			</th>
			<th scope="col" class="credits">
				'.__('Credits', _PLUGIN_NAME_).': <a href="#" target="_blank">'.$credits_remaining.'</a>
			</th>
			<th scope="col" class="volume'.$array_field_sorted['volume'].'">
				<a href="#" alt="volume" title="asc" class="wpst-sorting-arrows wpst-sorting-arrows-asc"></a>	
				<a href="#" alt="volume" title="desc" class="wpst-sorting-arrows wpst-sorting-arrows-desc"></a>
				'.__('Volume', _PLUGIN_NAME_).' <a href="#" class="button" title="'.__('Volume: search popularity for the keyword each months.', _PLUGIN_NAME_).'">?</a>
			</th>
			<th scope="col" class="cpc'.$array_field_sorted['cpc'].'">
				<a href="#" alt="cpc" title="asc" class="wpst-sorting-arrows wpst-sorting-arrows-asc"></a>	
				<a href="#" alt="cpc" title="desc" class="wpst-sorting-arrows wpst-sorting-arrows-desc"></a>
				'.__('CPC', _PLUGIN_NAME_).' <a href="#" class="button" title="'.__('CPC: cost per click.', _PLUGIN_NAME_).'">?</a>
			</th>
			<th scope="col" class="profit'.$array_field_sorted['profit'].'">
				<a href="#" alt="profit" title="asc" class="wpst-sorting-arrows wpst-sorting-arrows-asc"></a>	
				<a href="#" alt="profit" title="desc" class="wpst-sorting-arrows wpst-sorting-arrows-desc"></a>
				'.__('Profit', _PLUGIN_NAME_).' <a href="#" class="button" title="'.__('Profit: good keyword', _PLUGIN_NAME_).'">?</a>
			</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="5" id="keyword-scroller-td">
					<div id="keyword-table-container">
					<table id="wpst-table" class="widefat fixed">
						<tbody id="keyword-suggestions-results">';
							
		foreach ( $keyword_array as $out ) :
			if ( $out->keyword != '' ) :
				$url_blog = get_option('siteurl');
				$html .= '<tr>';
				$html .= '<td class="wpst-keyword-cell wpst-set-title keyword"">';
				$html .= '<strong><a href="#" class="wpst-set-title" keyword="' . $out->keyword . '">' . $out->keyword . '</a></strong><br/>';
				$html .= '<div class="wpst-row-actions">';
				$html .= '<span><a href="#" class="wpst-set-title" keyword="' . $out->keyword . '">'.__('Set as title', _PLUGIN_NAME_).'</a>  | </span>';
				$html .= '<span><a href="#" class="wpst-more" keyword="' . $out->keyword . '">'.__('More suggestions', _PLUGIN_NAME_).'</a>  | </span>';
				$html .= '<span><a href="'.$url_blog.'/wp-admin/edit.php?s=' . $out->keyword . '" target="_blank">'.__('Check existing', _PLUGIN_NAME_).'</a></span>';
				$html .= '</div>';
				$html .= '</td>';
				$html .= '<td class="volume">';
				$html .= '<strong>' . number_format( $out->volume, 0, '', '.' ) . '</strong>';
				$html .= '</td>';
				$html .= '<td class="cpc">';
				$html .= '<strong>' . number_format( $out->cpc, 2, ',', '.' ) . '</strong>';
				$html .= '</td>';
				$html .= '<td class="profit">';
				$html .= '<strong>' . number_format( $out->profit, 2, ',', '.' ) . '</strong>';
				$html .= '</td>';
				$html .= '</tr>';
			endif;
		endforeach;
		$html .= '
					</tbody>
				</table>
				</div>
			</td>
		</tr>

		</tbody>
		</table>';
		
		return $html;
	}
}

?>
