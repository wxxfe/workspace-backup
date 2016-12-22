(function() {
	var Previewer = window.Previewer = {
		init: function() {
			var htmlArr = ['<!DOCTYPE html>',
                    '<html><head>',
                    '<link rel=\'stylesheet\' type=\'text/css\' href=\'/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/flexible.css\'/>',
                    '<link rel=\'stylesheet\' type=\'text/css\' href=\'/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/news.css\'/>',
                    '<script type=\'text/javascript\' src=\'/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/flexible.js\'></script>',
                    '</head><body style=\'height: 667px;overflow-y:auto\'>', 
                    '<section class=\'section section-article\' style=\'margin-top: 0;\'>', 
	                '  <div class=\'section-content\'>',
	                '    <article class=\'article\'>',
			        '        <div class=\'article-headline\'>',
					'			<div class=\'wrapper\'>',
					'				<h1 class=\'title\'>这是一个假标题</h1>',
					'				<div class=\'footnote\'>',
					'					<span class=\'note\'>暴风体育</span>',
					'					<span class=\'note\'>2016-09-11 21:36</span>',
					'				</div>',
					'			</div>',
					'		</div>',
	                '      <div class=\'content\'>',
		            '        <div class=\'paragraphs\'></div>',
		            '      </div>',
		            '    </article>',
	                '  </div>',
                    '</section>',
                    '</body>',
                    '<script type=\'text/javascript\' src=\'/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/require.js\'></script>',
                    '<script type=\'text/javascript\' id=\'newsJs\' src=\'/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/news.js\'></script>',
                    '</html>'];
			var iframe = document.querySelector('.phone iframe');
			
			iframe.src = 'javascript:void(function(){document.open();document.write("' + htmlArr.join("") + '");document.close();}())';
		},
		reloadJs: function() {
			var doc = this.getDoc();
			doc.querySelector('#newsJs').remove();
			var script = doc.createElement('script');
			script.id="newsJs";
			script.src="/static/plugins/ueditor1_4_3_3-utf8/previewer/vendor/news.js";
			doc.body.appendChild(script);
		},
		getDoc: function() {
			return document.querySelector('.phone iframe').contentWindow.document;
		},
		setContent: function(content) {
			var doc = this.getDoc();
			doc.querySelector('.paragraphs').innerHTML = content;
		},
		reload: function() {
			this.setContent(BFEditor.getContent());
			this.reloadJs();
		},
		load: function() {
			this.reload();
		}
	}
	Previewer.init();
})()

