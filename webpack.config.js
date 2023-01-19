const Encore = require('@symfony/webpack-encore')
const fs = require('fs')
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}
Encore
  .setOutputPath('public/static/')
  .setPublicPath('/static')
  .splitEntryChunks()
  .configureSplitChunks(() => {
    return {
      chunks: 'all',
      minSize: 0
    }
  })
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage'
    config.shippedProposals = true
    config.corejs = '3.25'
    config.targets = 'defaults'
  })
  .enableSassLoader()
  .enablePostCssLoader()
  .enableTypeScriptLoader(
    (config) => {
      config.onlyCompileBundledFiles = true
      config.configFile = 'tsconfig.json'
    }
  )
  .enableIntegrityHashes(Encore.isProduction())
  .enableBuildCache({
    config: [__filename]
  })
if (fs.existsSync('entrypoints.json')) {
  const entrypointsJson = fs.readFileSync('entrypoints.json')
  const entrypoints = JSON.parse(entrypointsJson)
  for (const [key, value] of Object.entries(entrypoints)) {
    if (fs.existsSync(value)) {
      Encore.addEntry(key, value)
    } else {
      console.error(`Missing ${value}`)
    }
  }
}

module.exports = Encore.getWebpackConfig()
