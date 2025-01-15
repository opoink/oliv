/**
 * pm2 ecosystem config
 * https://pm2.keymetrics.io/docs/usage/quick-start/
 */

module.exports = {
	apps : [
		{
			script: './bootstrap/ssr/ssr.js',
			watch: 'true',
			env: {
				VITE_INERTIA_SSR_PORT: 13714
			}
		}
	]
};
