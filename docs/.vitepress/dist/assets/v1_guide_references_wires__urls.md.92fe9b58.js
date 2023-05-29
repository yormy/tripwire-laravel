import{_ as s,o as a,c as e,V as n}from"./chunks/framework.5f0cae6b.js";const _=JSON.parse('{"title":"","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/references/wires/_urls.md","filePath":"v1/guide/references/wires/_urls.md"}'),l={name:"v1/guide/references/wires/_urls.md"},o=n(`<h2 id="urls" tabindex="-1">Urls <a class="header-anchor" href="#urls" aria-label="Permalink to &quot;Urls&quot;">​</a></h2><p>This specifies which urls to include or exclude</p><h3 id="example" tabindex="-1">Example <a class="header-anchor" href="#example" aria-label="Permalink to &quot;Example&quot;">​</a></h3><p>Include all urls that start with <code>members/...</code> however do not include <code>members/dashboard</code></p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">urls</span><span style="color:#89DDFF;">(</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">UrlsConfig</span><span style="color:#89DDFF;">::</span><span style="color:#82AAFF;">make</span><span style="color:#89DDFF;">()</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">only</span><span style="color:#89DDFF;">([</span></span>
<span class="line"><span style="color:#A6ACCD;">                </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">members/*</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">])</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">except</span><span style="color:#89DDFF;">([</span></span>
<span class="line"><span style="color:#A6ACCD;">                </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">members/dashboard</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">]</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">))</span></span></code></pre></div>`,5),p=[o];function r(t,c,i,D,d,F){return a(),e("div",null,p)}const h=s(l,[["render",r]]);export{_ as __pageData,h as default};
