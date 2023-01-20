# MODULE OVERVIEW

The module implements a REST Resource exposing a REST endpoint for creating nodes of the type "article" with multiple referenced Paragraphs of different types.

Authentication is done with BasicAuth and has the module "HTTP Basic Auth" as a dependancy.

# TO TEST

1. Install the module
2. Open your favorite REST client
3. Method: POST
4. Endpoint url: https://DOMAIN:COM/custom_rest_resource/custom_resource?_format=json
5. JSON data format:
```json
  {
	"paragraphs": [
	{
		"type": "text",
		"title": "Text paragraph Title",
		"text": "<p>Text paragraph body</p>"
	},
	{
		"type": "image",
		"url": "https://drupalize.me/sites/default/files/page_images/wordmark2_blue_rgb.png",
		"name": "image_name.png",
		"alt_text": "Image alt text"
	},
	{
		"type": "text",
		"title": "Text paragraph Title alt",
		"text": "<p>Text paragraph Body alt</p>"
	}],
	"node":
	{
		"title": "Node title"
	}
}
```
6. On success the JSON reponse is the newly created node object.
