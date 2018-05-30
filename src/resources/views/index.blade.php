@extends('layouts.app')

@section('title', '')

@section('content')

<!--.home start-->
<div class="container home font-white spec" id="home">
	<div class="body">
		<h2 class="wow fadeInDown">
			{{ __('Xpense – extracting value from individual consumption and consumer behavior') }}			
		</h2>
		<ul>
			<li class=" wow fadeInLeft" data-wow-delay=".3s">
				@if (app()->getLocale() == 'en')
				<a href="/static/data/project_white_paper_en.pdf" class="button button-block button-cyan">{{ __('DOWNLOAD WHITEPAPER') }}</a>
				@else
				<a href="/static/data/project_white_paper.pdf" class="button button-block button-cyan">{{ __('DOWNLOAD WHITEPAPER') }}</a>
				@endif				
			</li>
			<!-- <li class="wow fadeInRight" data-wow-delay=".5s">
				<a href="/static/data/project_presentation.pdf"" class="button button-block button-cyan">下载项目PPT</a>
			</li> -->
		</ul>
		<br></br>
		<br></br>
		<h4>
			{{ __('Xpense will start a revolution on offline consumer experience and attract more users returning to brick and mortar stores.') }}
		    
		</h4>
	</div>
</div>
<!--.home end-->
<!--.product start-->
<div class="container product" id="product">
	<div class="body">
		<h2 class="wow pulse" >
			{{ __('Project Highlights') }}
		</h2>
		<div class="product-list">
			<dl rel="hover" class="wow fadeInLeft">
				<dt>
					{{ __('Mining through facial recognition') }}
				</dt>
				<dd>
					<span class="icon icon-light-1"></span>
				</dd>
				<dd class="exp exp-left font-gray">
				{{ __('The age of living off your face has come! Consumers can be rewarded tokens by scanning their faces at the offline stores which join Xpense platform program. Tokens can be converted into cash on third-party market or exchanged for all sorts of goods and services from the stores.') }}
				</dd>
			</dl>
			<dl rel="hover" class="wow fadeInRight">
				<dt>
				{{ __('KYC Capability') }}
				</dt>
				<dd>
					<span class="icon icon-light-2"></span>
				</dd>
				<dd class="exp exp-right font-gray">
				{{ __('Under traditional business model, offline stores are not able to obtain the background information of each new customer, so targeted selling can not be achieved. While stores joined Xpense are empowered with the KYC (Know Your Customer) capability, therefore they can easily acquire the consumption portrait and other information of every client who has visited their stores. This is essential for them to improve the Price per Customer and success rate of sales.') }}    
				</dd>
			</dl>
		</div>
		<div class="product-list">
			<dl rel="hover" class="wow fadeInLeft">
				<dt>
				{{ __('Making shopping full of fun') }}
				</dt>
				<dd>
					<span class="icon icon-light-3"></span>
				</dd>
				<dd class="exp exp-left font-gray">
				{{ __('Xpense itself or stores on the platform can launch various shopping tasks based on facial recognition. After the completion of these tasks, consumers will be rewarded with token. These game-like tasks will reshape the offline consumption experience and make shopping much more interesting.') }}
				</dd>
			</dl>
			<dl rel="hover" class="wow fadeInRight">
				<dt>
				{{ __('Helping stores win more customers') }}
				</dt>
				<dd>
					<span class="icon icon-light-4"></span>
				</dd>
				<dd class="exp exp-right font-gray">
				{{ __('Joining Xpense enables stores to access consumption portraits of the people nearby and push precise marketing information to them. This will tremendously improve stores’ ability to gain more customers because the people nearby are the most likely potential customers.') }}
				</dd>
			</dl>
		</div>
	</div>
</div>
<!--.product end-->
<!--.architecture start-->
<div class="container architecture" id="architecture">
	<div class="architecture-cover">
		<div class="cover-left">
			<div class="cover-inner">
				<h2 class="font-white wow fadeInDown">{{ __('Project Technical Architecture') }}<br />{{ __('and Product Realization') }}</h2>				
				@if (app()->getLocale() == 'en')
				<a href="/static/data/project_white_paper_en.pdf" class="button button-blue wow fadeIn" data-wow-delay=".5s">{{ __('DOWNLOAD WHITEPAPER') }}</a>
				@else
				<a href="/static/data/project_white_paper.pdf" class="button button-blue wow fadeIn" data-wow-delay=".5s">{{ __('DOWNLOAD WHITEPAPER') }}</a>
				@endif	
			</div>
		</div>
		<div class="cover-right">
		</div>
	</div>
	<div class="architecture-content wow fadeInRight" data-wow-offset="2">
		<h3>{{ __('Design of Xpense consumption & behavior data platform') }}</h3>
		<p class="font-gray">
		{{ __('Through the use of blockchain technology, Xpense will record all consumption and behavioral data involved by initially recording data on the side chain or off the chain and finally writing data into the backbone of Xpense data management chain. This is to ensure its growth comes from the records of consumption and behavioral data and avoid alteration or falsification. In the future, with the blockchain distributed storage technology getting more mature, the backbone of Xpense data management chain will also be transferred or stored on blockchain distributed storage. For truly achieving decentralization, we have adopted the following technologies.') }}
		</p>
		<p class="font-gray">
			1.{{ __('Blockchain based distributed ledger technology') }}
			<br />
			2.{{ __('Side- chain and cross-chain trading technology') }}
			<br />
			3.{{ __('DPOS consensus algorithm') }}
			<br />
			4.{{ __('Smart AI video recognition technology') }}
		</p>
		<div>
			<img src="/static/images/architecture_exp.jpg"" />
		</div>
	</div>
	<div class="clear"></div>
</div>
<!--.architecture end-->

<!--.media start-->
<div class="container gray-bg media" id="media">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Media Report') }}</h2>
		
		<div class="media-video flex-cont wrap-box">
			<div class="video">
				<video width="100%" controls autobuffer poster="/static/images/media-pre.png">
				    <source src="/static/media/about.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'></source>
				</video>
			</div>
		</div>

		<div class="media-list flex-cont wrap-box">
			<div class="url-link flex-cont">
				<div class="pic">
					<img src="/static/images/media-1.png">
				</div>
				<div class="cont">
					<div class="tit"><a href="http://industry.caijing.com.cn/20180521/4456222.shtml" target="_blank">打造消费新生态 超级买获原时资本500万...</a></div>
					<div class="name">财经网</div> 					
				</div>
			</div>
			<div class="url-link flex-cont">
				<div class="pic">
					<img src="/static/images/media-2.png">
				</div>
				<div class="cont">
					<div class="tit">
						<a href="http://news.ifeng.com/a/20180505/58116983_0.shtml" target="_blank">Xpense超级买与名品导购达成战略合作</a>
					</div>
					<div class="name">凤凰网</div> 					
				</div>
			</div>
			<div class="url-link flex-cont">
				<div class="pic">
					<img src="/static/images/media-3.png">
				</div>
				<div class="cont">
					<div class="tit"><a href="https://c.m.163.com/news/a/DI0IOK740511ROIS.html" target="_blank">用大数据描绘用户画像 XPENSE超级买...</a></div>
					<div class="name">网易新闻</div> 					
				</div>
			</div>
			<div class="url-link flex-cont">
				<div class="pic">
					<img src="/static/images/media-4.png">
				</div>
				<div class="cont">
					<div class="tit"><a href="http://ln.qq.com/a/20180508/030934.htm" target="_blank">改变固有模式 XPENSE超级买打造...</a></div>
					<div class="name">腾讯网</div> 					
				</div>
			</div>
		</div>
	</div>
</div>
<!--.media end-->

<!--.team start-->
<div class="container team" id="team">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Our Team') }}</h2>
		<div class="member-list">
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_mm.jpg"" /></dd>
				<dt>Marcos Meibergen</dt>
				<dd class="font-gray">CEO</dd>
			</dl>
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_gh.jpg"" /></dd>
				<dt>Ginger Ho</dt>
				<dd class="font-gray">CTO</dd>
			</dl>
			<dl class="wow bounceIn">					
				<dd><img src="/static/images/pic_th_1.jpg""/></dd>
				<dt>Thomas HSUEH</dt>
				<dd class="font-gray">Partner APAC,PR</dd>
			</dl>
		</div>
	</div>
</div>
<!--.team end-->
<!--.roadmap start-->
<div class="container roadmap" id="roadmap">
	<div class="body">
		<h2 class="wow fadeInLeft">{{ __('Project Milestones') }}</h2>
		<div class="road-list">
			<dl class="list-1 wow fadeIn">
				<dd class="font-gray">
					<div>
						{{ __('Initial planning of') }} <br />{{ __('the project starts') }}
					</div>
				</dd>
				<dt>{{ __('Nov.2017') }}</dt>
				<dd>
					<span class="icon icon-video"></span>
				</dd>
			</dl>
			<div class="road road-1 wow fadeIn" data-wow-delay="1.2s"></div>
			<dl class="list-2 wow fadeIn" data-wow-delay=".2s">
				<dd class="font-gray">
					<div>
						{{ __('Completion of the Core Team') }}
					</div>
				</dd>
				<dt>{{ __('Dec.2017') }}</dt>
				<dd>
					<span class="icon icon-group-1"></span>
				</dd>
			</dl>
			<div class="road road-2 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-2-1 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-2-2 wow fadeIn" data-wow-delay="1.2s"></div>
			<dl class="list-3 wow fadeIn wow fadeIn" data-wow-delay=".4s">
				<dd class="font-gray">
					<div>
						{{ __('Completion of prototype') }}
					</div>
				</dd>
				<dt>{{ __('Jan.2018') }}</dt>
				<dd>
					<span class="icon icon-document"></span>
				</dd>
			</dl>
			<div class="road road-3 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-3-1 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-3-2 wow fadeIn" data-wow-delay="1.2s"></div>
			<dl class="list-4 wow fadeIn" data-wow-delay=".8s">
				<dd>
					<span class="icon icon-chat"></span>
				</dd>
				<dt>{{ __('Feb.2018') }}</dt>
				<dd class="font-gray">
					<div>
						{{ __('Launch of the website and white paper') }}
					</div>
				</dd>
			</dl>
			<div class="road road-4 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-4-1 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-4-2 wow fadeIn" data-wow-delay="1.2s"></div>
			<dl class="list-5 wow fadeIn" data-wow-delay="1s">
				<dd>
					<span class="icon icon-global"></span>
				</dd>
				<dt>{{ __('Aug.2018') }}</dt>
				<dd class="font-gray">
					<div>
						{{ __('Building the basic infrastructure') }}
					</div>
				</dd>
			</dl>
			<!-- <div class="road road-4 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-4-1 wow fadeIn" data-wow-delay="1.2s"></div>
			<div class="road road-4-2 wow fadeIn" data-wow-delay="1.2s"></div>
			<dl class="list-5 wow fadeIn" data-wow-delay="1s">
				<dd>
					<span class="icon icon-global"></span>
				</dd>
				<dt>2018年11月</dt>
				<dd class="font-gray">
					<div>
						APP上线
					</div>
				</dd>
			</dl> -->
		</div>
	</div>
</div>
<!--.roadmap end-->
<!--.consultants start-->
<div class="container consultants" id="consultants">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Project Consultants') }}</h2>
		<div class="member-list">
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_Zhupan.png"" /></dd>
				<dt>{{ __('Pan ZHU') }}</dt>
				<dd class="font-gray">{{ __('Fintech Consultant') }}</dd>
			</dl>
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_pz_GuanWenSheng.png""/></dd>
				<dt>{{ __('Guan Wen Sheng') }}</dt>
				<dd class="font-gray">{{ __('Founder of Ji Zhi Hui Capital') }}</dd>
			</dl>
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_lt.jpg""/></dd>
				<dt>{{ __('Lenit TUNG') }}</dt>
				<dd class="font-gray">{{ __('Chief Consultant') }}</dd>
			</dl>
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_hx.jpg"" /></dd>
				<dt>{{ __('Hangxing XIE') }}</dt>
				<dd class="font-gray">{{ __('Chief Scientist') }}</dd>
			</dl>
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_pz_1.jpg""/></dd>
				<dt>{{ __('Peng ZUO') }}</dt>
				<dd class="font-gray">{{ __('Chairman of Jingqiu Technology') }}</dd>
			</dl>			
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_plt_1.jpg"" /></dd>
				<dt>{{ __('Peiling TSUI') }}</dt>
				<dd class="font-gray">{{ __('Expert of investment Risk Control') }}</dd>
			</dl>				
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_Zhangaoyun.png"" /></dd>
				<dt>{{ __('Aoyun ZHANG') }}</dt>
				<dd class="font-gray">{{ __('Financial Consultant') }}</dd>
			</dl>										
			<dl class="wow bounceIn">
				<dd><img src="/static/images/pic_yz.jpg"" /></dd>
				<dt>{{ __('Yiyun ZHANG') }}</dt>
				<dd class="font-gray">{{ __('Geekbeans CEO') }}</dd>
			</dl>
		</div>
	</div>
</div>
<!--.consultants end-->
<!--.partner start-->
<div class="container partner gray-bg" id="partners">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Partners') }}</h2>
		<div class="logo-list body">
			<ul>
				<li class="wow pulse"><img src="/static/images/logo_gingkoo.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_chain_capital.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_geekbeans.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_tac.png""/></li>
			</ul>
		</div>
	</div>
</div>
<!--.partner end-->
<!--.project start-->
<div class="container project" id="project">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Cooperation Projects') }}</h2>
		<div class="logo-list body">
			<ul>
				<li class="wow pulse"><img src="/static/images/logo_sands.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_dillards.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_ca.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_printemps.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_lfy.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_deb.png""/></li>
			</ul>
		</div>
	</div>
</div>
<!--.project end-->
<!--.partener-1 start-->
<div class="container partner-1 gray-bg">
	<div class="body">
		<h2 class="wow fadeInDown">{{ __('Media Partners') }}</h2>
		<div class="logo-list body">
			<ul>
				<li class="wow pulse"><img src="/static/images/partener_2.png""/></li>
				<li class="wow pulse"><img src="/static/images/partener_3.png""/></li>
				<li class="wow pulse"><img src="/static/images/partener_4.png""/></li>
				<li class="wow pulse"><img src="/static/images/partener_5.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_bitekuaixun.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo-jinsecaijing.svg""/></li>
				<li class="wow pulse"><img src="/static/images/logo_news-now.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_kcbd.png""/></li>
				<li class="wow pulse"><img src="/static/images/logo_25abc.png""/></li>
				<li class="wow pulse"><img src="/static/images/hq_logo.png""/></li>
				<!--
				<li><img src="images/partener_0.png"/></li>
				-->
			</ul>
		</div>
	</div>
</div>
	<!--.partener-1 end-->

@endsection