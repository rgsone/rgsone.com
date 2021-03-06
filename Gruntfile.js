module.exports = function( grunt )
{
	grunt.initConfig({

		pkg: grunt.file.readJSON( 'package.json' ),

		watch: {
			css: {
				files: [
					'public/css/*.css',
					'!public/css/*.min.css',
					'!public/css/*.pre.css',
					'app/templates/*.tmpl'
				],
				tasks: [ 'buildcss' ],
				options: {
					interrupt: true,
					livereload: true
				}
			},
			js: {
				files: [
					'public/js/app.js'
				],
				tasks: [ 'buildjs' ],
				options: {
					interrupt: true,
					livereload: true
				}
			}
		},

		copy: {
			distbuild: {
				src: [
					'app/**',
					'data/**',
					'public/**',
					'.htaccess'
				],
				dest: 'dist/',
				dot: true
			},
			cssbuild: {
				files: {
					'public/css/rgsone.pre.css': [ 'public/css/rgsone.css' ]
				}
			},
			jsbuild: {
				files: {
					'public/js/html5shiv.js': [ 'public/bower-components/html5shiv/dist/html5shiv.js' ]
				}
			}
		},

		clean: {
			fulldist: {
				src: [
					'dist/*'
				],
				dot: true
			},
			dist: {
				src: [
					'dist/**/.gitkeep',
					'dist/app/cache/*',
					'dist/app/libs/**/*.git',
					'dist/public/bower-components/',
					'dist/public/css/*.css',
					'!dist/public/css/*.min.css',
					'dist/public/js/**/*.js',
					'!dist/public/js/vendor/vendor.min.js',
					'!dist/public/js/app.min.js',
					'!dist/public/js/html5shiv.js'
				],
				dot: true
			},
			appcache: [
				'app/cache/__Mustache*'
			],
			cssbuild: [
				'public/css/*.pre.css'
			]
		},

		concat: {
			css: {
				src: [
					'public/bower-components/normalize.css/normalize.min.css',
					'public/css/rgsone.min.css'
				],
				dest: 'public/css/rgsone.min.css'
			},
			js: {
				files: {
					'public/js/vendor/vendor.min.js': [ 'public/js/vendor/*.min.js' ]
				}
			}
		},

		cssmin: {
			process: {
				options: {
					report: 'gzip',
					keepBreaks: false,
					removeEmpty: true
				},
				files: {
					'public/css/rgsone.min.css': ['public/css/rgsone.pre.css'],
					'public/bower-components/normalize.css/normalize.min.css': ['public/bower-components/normalize.css/normalize.css']
				}
			}
		},

		autoprefixer: {
			prefix: {
				src: 'public/css/*.pre.css'
			}
		},

		csscomb: {
			sort: {
				options: {
					config: 'csscomb.json'
				},
				files: {
					'public/css/rgsone.css': [ 'public/css/rgsone.css' ]
				}
			}
		},

		uglify: {
			options: {
				preserveComments: 'some',
				report: 'gzip'
			},
			app: {
				files: {
					'public/js/app.min.js': [ 'public/js/app.js' ]
				}
			},
			vendor: {
				files: {
					'public/js/vendor/matchmedia.min.js': [ 'public/bower-components/matchmedia/matchMedia.js' ],
					'public/js/vendor/raf.min.js': [ 'public/bower-components/raf.js/raf.js' ]
				}
			}
		}
	});

	////// LOADTASK //////

	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-autoprefixer' );
	grunt.loadNpmTasks( 'grunt-csscomb' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );

	////// REGISTER TASK //////

	grunt.registerTask( 'default', 'Log some stuff.', function() {

		grunt.log.write( '--' );

	});

	grunt.registerTask( 'csssort', [ 'csscomb' ] );
	grunt.registerTask( 'buildcss',  [ 'copy:cssbuild', 'autoprefixer:prefix', 'cssmin', 'concat:css', 'clean:cssbuild' ] );
	grunt.registerTask( 'buildjs',  [ 'uglify', 'concat:js' ] );
	grunt.registerTask( 'builddist',  [ 'clean:fulldist', 'copy:distbuild', 'clean:dist' ] );
	grunt.registerTask( 'cleancache',  [ 'clean:appcache' ] );
};
