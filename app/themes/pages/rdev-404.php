<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	$this->GetHeader();
?>
			<section id="404" style="position: relative;display: flex;align-items: center;justify-content: center;flex-flow: column;height:100%;width:100%;min-height: 100vh;background: #555;text-align: center;">
				<canvas id="noise" style="z-index:100;position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;opacity:.05;"></canvas>
				<h1 style="font-size: 150px;color:#fff;" class="glitch-title" data-content="404!">404!</h1>
				<h2 style="font-size: 20px;color:#fff;">This should not happen</h2>
			</section>
			<script type="text/javascript" nonce="<?php echo $this->js_nonce; ?>">
				const noise=()=>{let e,t,n,i,o,a=[],d=0;const r=()=>{const e=t.createImageData(n,i),o=new Uint32Array(e.data.buffer),d=o.length;for(let e=0;e<d;e++)Math.random()<.5&&(o[e]=4278190080);a.push(e)},w=()=>{9===d?d=0:d++,t.putImageData(a[d],0,0),o=window.setTimeout(()=>{window.requestAnimationFrame(w)},40)},s=()=>{n=window.innerWidth,i=window.innerHeight,e.width=n,e.height=i;for(let e=0;e<10;e++)r();w()};e=document.getElementById("noise"),t=e.getContext("2d"),s()};noise();
			</script>
<?php
	$this->GetFooter();
?>
