import{_ as s,o as a,c as e,V as o}from"./chunks/framework.5f0cae6b.js";const u=JSON.parse('{"title":"Routes","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/advanced/setup/routes.md","filePath":"v1/guide/advanced/setup/routes.md"}'),n={name:"v1/guide/advanced/setup/routes.md"},l=o(`<h1 id="routes" tabindex="-1">Routes <a class="header-anchor" href="#routes" aria-label="Permalink to &quot;Routes&quot;">​</a></h1><p>Sometimes you might want to exclude one or more middelwares from a route. You can add the <code>withoutMiddleware([])</code> to you route group to not apply that middleware checker</p><h2 id="removing-a-group-of-middlewares" tabindex="-1">Removing a group of middlewares <a class="header-anchor" href="#removing-a-group-of-middlewares" aria-label="Permalink to &quot;Removing a group of middlewares&quot;">​</a></h2><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#FFCB6B;">Route</span><span style="color:#89DDFF;">::</span><span style="color:#82AAFF;">prefix</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;&#39;</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">withoutMiddleware</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">group</span><span style="color:#89DDFF;">(</span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">()</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">{</span></span></code></pre></div><h2 id="removing-a-single-middleware" tabindex="-1">Removing a single middleware <a class="header-anchor" href="#removing-a-single-middleware" aria-label="Permalink to &quot;Removing a single middleware&quot;">​</a></h2><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#FFCB6B;">Route</span><span style="color:#89DDFF;">::</span><span style="color:#82AAFF;">prefix</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;&#39;</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">withoutMiddleware</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.honeypotwire</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">group</span><span style="color:#89DDFF;">(</span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">()</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">{</span></span></code></pre></div>`,6),p=[l];function t(r,c,i,d,F,D){return a(),e("div",null,p)}const m=s(n,[["render",t]]);export{u as __pageData,m as default};