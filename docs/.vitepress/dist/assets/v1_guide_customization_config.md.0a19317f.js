import{_ as s,o,c as a,V as e}from"./chunks/framework.5f0cae6b.js";const g=JSON.parse('{"title":"Customizing the config","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/customization/config.md","filePath":"v1/guide/customization/config.md"}'),t={name:"v1/guide/customization/config.md"},n=e('<h1 id="customizing-the-config" tabindex="-1">Customizing the config <a class="header-anchor" href="#customizing-the-config" aria-label="Permalink to &quot;Customizing the config&quot;">​</a></h1><p>In order to make detailed changes to the config and wires you need to publish them first.</p><div class="language-bash"><button title="Copy Code" class="copy"></button><span class="lang">bash</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#FFCB6B;">php</span><span style="color:#A6ACCD;"> </span><span style="color:#C3E88D;">artisan</span><span style="color:#A6ACCD;"> </span><span style="color:#C3E88D;">vendor:publish</span><span style="color:#A6ACCD;"> </span><span style="color:#C3E88D;">--provider=</span><span style="color:#89DDFF;">&quot;</span><span style="color:#C3E88D;">Yormy\\TripwireLaravel\\TripwireServiceProvider</span><span style="color:#89DDFF;">&quot;</span><span style="color:#A6ACCD;"> </span><span style="color:#C3E88D;">--tag=</span><span style="color:#89DDFF;">&quot;</span><span style="color:#C3E88D;">config</span><span style="color:#89DDFF;">&quot;</span></span></code></pre></div>',3),i=[n];function c(p,r,l,d,u,h){return o(),a("div",null,i)}const m=s(t,[["render",c]]);export{g as __pageData,m as default};