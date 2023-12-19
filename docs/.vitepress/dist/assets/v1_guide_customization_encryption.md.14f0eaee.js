import{_ as s,o as a,c as n,V as p}from"./chunks/framework.5f0cae6b.js";const A=JSON.parse('{"title":"Encryption of sensitive data","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/customization/encryption.md","filePath":"v1/guide/customization/encryption.md"}'),l={name:"v1/guide/customization/encryption.md"},o=p(`<h1 id="encryption-of-sensitive-data" tabindex="-1">Encryption of sensitive data <a class="header-anchor" href="#encryption-of-sensitive-data" aria-label="Permalink to &quot;Encryption of sensitive data&quot;">​</a></h1><div class="tip custom-block"><p class="custom-block-title">Goal</p><p>To ensure your database privacy settings you can encrypt values.</p></div><p>Tripwire stores sensitive data as the IP address. You might want to encrypt that data. Tripwire itself does not include the encryption. All (except ip, userId, userType and browserfingerprint) can be encrypted without any additional efforts as there are no searches on those fields.</p><ul><li>userId,userType and browserfingerprint are non privacy details, so they should not be encrypted to ease searching.</li><li>IP address is privacy, and hence could be encrypted. If you do so you also need to make sure searching is still possible to block repeated offenders with the same ip. For searching to be possible implement <code>scopeByIp</code></li></ul><h1 id="setup" tabindex="-1">Setup <a class="header-anchor" href="#setup" aria-label="Permalink to &quot;Setup&quot;">​</a></h1><p>Overwrite <code>TripwireLog</code> and <code>TripwireBlock</code> so that the encrypted values are...encrypted Then overwrite the scopeByIp to search according to your encryption technology. In the following example I use ciphersweet to use encrypted search.</p><h3 id="alternative-approach" tabindex="-1">Alternative approach <a class="header-anchor" href="#alternative-approach" aria-label="Permalink to &quot;Alternative approach&quot;">​</a></h3><p>Encrypt however you like and include a hash of the ip. Then make sure the scopeByIp creates the hash from the paramater and searches the database</p><h1 id="example-with-the-use-of-ciphersweet" tabindex="-1">Example with the use of CipherSweet <a class="header-anchor" href="#example-with-the-use-of-ciphersweet" aria-label="Permalink to &quot;Example with the use of CipherSweet&quot;">​</a></h1><p>Add your new models to the config.</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#676E95;font-style:italic;">// tripwire.php</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">models</span><span style="color:#89DDFF;">(</span><span style="color:#FFCB6B;">MyTripwireLog</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">MyTripwireBlock</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">)</span></span></code></pre></div><h3 id="overwrite-tripwirelog" tabindex="-1">Overwrite TripwireLog <a class="header-anchor" href="#overwrite-tripwirelog" aria-label="Permalink to &quot;Overwrite TripwireLog&quot;">​</a></h3><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">&lt;?</span><span style="color:#A6ACCD;">php</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">namespace</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">App</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">Models</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Spatie</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">LaravelCipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Concerns</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">UsesCipherSweet</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">ParagonIE</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">EncryptedRow</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">ParagonIE</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">BlindIndex</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Spatie</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">LaravelCipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Contracts</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweetEncrypted</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Yormy</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">TripwireLaravel</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Models</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">TripwireLog</span><span style="color:#FFCB6B;"> </span><span style="color:#F78C6C;">as</span><span style="color:#FFCB6B;"> BaseTripwireLog</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#C792EA;">class</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">MyTripwireLog</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">extends</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">BaseTripwireLog</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">implements</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">CipherSweetEncrypted</span></span>
<span class="line"><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">UsesCipherSweet</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#C792EA;">public</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">static</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#82AAFF;">configureCipherSweet</span><span style="color:#89DDFF;">(</span><span style="color:#FFCB6B;">EncryptedRow</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">encryptedRow</span><span style="color:#89DDFF;">):</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">void</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">encryptedRow</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">addField</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">addBlindIndex</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">new</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">BlindIndex</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">ip_index</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">));</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">}</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#C792EA;">public</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#82AAFF;">scopeByIp</span><span style="color:#89DDFF;">($</span><span style="color:#A6ACCD;">query</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">string</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">ipAddress</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;font-style:italic;">return</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">query</span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">whereBlind</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">ip_index</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">ipAddress</span><span style="color:#89DDFF;">);</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">}</span></span>
<span class="line"><span style="color:#89DDFF;">}</span></span></code></pre></div><h3 id="overwrite-tripwireblock" tabindex="-1">Overwrite TripwireBlock <a class="header-anchor" href="#overwrite-tripwireblock" aria-label="Permalink to &quot;Overwrite TripwireBlock&quot;">​</a></h3><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">&lt;?</span><span style="color:#A6ACCD;">php</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">namespace</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">App</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">Models</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Spatie</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">LaravelCipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Concerns</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">UsesCipherSweet</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">ParagonIE</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">EncryptedRow</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">ParagonIE</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">BlindIndex</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Spatie</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">LaravelCipherSweet</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Contracts</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">CipherSweetEncrypted</span><span style="color:#89DDFF;">;</span></span>
<span class="line"><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">Yormy</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">TripwireLaravel</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Models</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">TripwireBlock</span><span style="color:#FFCB6B;"> </span><span style="color:#F78C6C;">as</span><span style="color:#FFCB6B;"> BaseTripwireBlock</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#C792EA;">class</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">MyTripwireBlock</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">extends</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">BaseTripwireBlock</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">implements</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">CipherSweetEncrypted</span></span>
<span class="line"><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#F78C6C;">use</span><span style="color:#FFCB6B;"> </span><span style="color:#A6ACCD;">UsesCipherSweet</span><span style="color:#89DDFF;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#C792EA;">public</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">static</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#82AAFF;">configureCipherSweet</span><span style="color:#89DDFF;">(</span><span style="color:#FFCB6B;">EncryptedRow</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">encryptedRow</span><span style="color:#89DDFF;">):</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">void</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">encryptedRow</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">addField</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">blocked_ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">            </span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">addBlindIndex</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">blocked_ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">new</span><span style="color:#A6ACCD;"> </span><span style="color:#FFCB6B;">BlindIndex</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">blocked_ip_index</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">));</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">}</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#C792EA;">public</span><span style="color:#A6ACCD;"> </span><span style="color:#C792EA;">function</span><span style="color:#A6ACCD;"> </span><span style="color:#82AAFF;">scopeByIp</span><span style="color:#89DDFF;">($</span><span style="color:#A6ACCD;">query</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#F78C6C;">string</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">ipAddress</span><span style="color:#89DDFF;">)</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">{</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;font-style:italic;">return</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">query</span><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">whereBlind</span><span style="color:#89DDFF;">(</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">blocked_ip</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">blocked_ip_index</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">ipAddress</span><span style="color:#89DDFF;">);</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">}</span></span>
<span class="line"><span style="color:#89DDFF;">}</span></span></code></pre></div>`,15),e=[o];function t(r,c,y,D,F,C){return a(),n("div",null,e)}const d=s(l,[["render",t]]);export{A as __pageData,d as default};