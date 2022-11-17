# 1.7.0

- Added stages:
 - `IStageApiAfterCreate`
 - `IStageApiAfterUpdate`
 - `IStageApiAfterDelete`

# 1.6.0

- Added route `/help` with a list of all available routes. 

# 1.5.0

- Added enrich stages for create and update.
- Added bulletproof for all types of methods (create, update, list, view, delete).

# 1.4.0

- Added `getRequestParameter(string $paramName, string $default = '')` (see https://github.com/jeyroik/extas-api/issues/14)
- Improve `getRequestData()` - now it returns `args` data too (see https://github.com/jeyroik/extas-api/issues/13)
- Fixed `getRequestData()` multiple calls.
- Fixed `IRoute` description.
  
# 1.3.1

- Fixed #11

# 1.3.0

- Added stage `IStageInputDescription`.
- Added stage `IStageOutputDescription`.
- Added plugin `PluginInputValidators`.
- Added plugin `PluginOutputValidators`.
- Added `IValidator` interface.
- Added class `Validator`.
- Added validator `VUUID`.
- Updated readme.

# 1.2.2

- Fixed api description.
- Added test coverage.

# 1.2.1

- Added test coverage.

# 1.2.0

- Added 
 - `IRouteDispatcher` + `RouteDispatcher`
 - `IJsonDispatcher` + `JsonDispatcher`
 - `IHaveApiDescription` + `THasApiDescription`
 - `IJsonSchemaV1`
- Added tools to simplify CRUD constructing:
 - `TRouteCreate`
 - `TRouteView`
 - `TRouteList`
 - `TRouteUpdate`
 - `TRouteDelete`
 - `TRouteHelp`

See tests for details.

# 1.1.0

- Added `IRoute`, `Route`, `IHaveDispatcher`, `THasDispatcher`, `PluginRoutes`.

# 1.0.1

- Updated to dotenv v5

# 1.0.0

- Updated to extats-foundation v6

# 0.1.1

- Using `cwd` instead of `EXTAS__BASE_PATH` for an application init.

# 0.1.0

- Project initialized.