# Installation

## 1. Download

    composer require ibanawx/prooph-event-store-rest-api-bundle
    
## 2. Bundle Registration

Register the following bundles in your Symfony kernel:

1. `Prooph\Bundle\EventStore\ProophEventStoreBundle`
2. `Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\ProophEventStoreRestApiBundle`

## 3. Import Routes

    # app/config/routing.yml
    
    prooph_event_store:
      resource: '@ProophEventStoreRestApiBundle/Resources/config/routing.yml'
      prefix: /event-store
      
The `prefix` option above is optional but useful if you'd like to separate this bundles routes from the other routes in your application.

# Configuration

    # app/config/config.yml
    
    prooph_event_store:
      stores:
        default:
          adapter: event_store_adapter_service_id
   
    prooph_event_store_rest_api:
      event_store:
        name: default
      formatters:
        stream: ~
        event: ~
        
For more information on configuring the `prooph_event_store` bundle click [here](https://github.com/prooph/event-store-symfony-bundle).

`prooph_event_store_rest_api`
<table>
  <tr>
    <th>Option</th>
    <th>Description</th>
    <th>Default</th>
  </tr>
  <tr>
    <td>event_store.name</td>
    <td>The name of the event store. Defined in the prooph_event_store config.</td>
    <td>No default</td>
  </tr>
  <tr>
    <td>event_store.formatters.stream</td>
    <td>Service ID of the stream formatter.</td>
    <td>prooph_event_store_rest_api.json_stream_formatter</td>
  </tr>
  <tr>
    <td>event_store.formatters.event</td>
    <td>Service ID of the stream event formatter.</td>
    <td>prooph_event_store_rest_api.json_stream_event_formatter</td>
  </tr>
</table>

# Usage

**All HTTP requests to the REST API must contain at least an `Accept` header specifying the content type you accept. The accepted content type is directly related to the output content type of the [formatter](#formatters).**

## Getting a Stream
 
#### Request Placeholders:

- `streamName`: The name of the stream.
- `minVersion`: The minimum event version that will appear in the stream.

<table>
  <tr>
    <th>Request</th>
    <th>Response</th>
    <th>Info</th>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/{minVersion}</td>
    <td>415 Unsupported Media Type</td>
    <td>If the 'Accept' header in the request is not the same as the output content type of the formatter. The 'Content-Type' of the response will be the content type that is supported by the server.</td>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/{minVersion}</td>
    <td>404 Not Found</td>
    <td>If the stream does not exist.</td>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/{minVersion}</td>
    <td>200 Ok</td>
    <td>The body of the response will contain the stream and its events.</td>
  </tr>
</table>

#### Succesful Response

By default, a response with an `application/json` content type will be returned. This is because the default [formatter](#formatters) formats the stream to JSON.

The default JSON formatter closely follows [The Atom Syndication Format](https://tools.ietf.org/html/rfc4287) but does not match it perfectly.

Let's say you have a `user` stream with **0** events. Sending a GET request to `http://site.com/streams/user/0` will respond with this:
    
    {
      "id": "http://site.com/streams/user/0",
      "title": "user stream",
      "links": [],
      "entries": []
    }
Let's say you have a `user` stream with **2** events. Sending a GET request to `http://site.com/streams/user/0` will respond with this:
    
    {
      "id": "http://site.com/streams/user/0",
      "title": "user stream",
      "links": [
        {
          "uri": "http://site.com/streams/user/2",
          "relation": "next"
        }
      ],
      "entries": [
        {
          "id": "http://site.com/streams/user/events/0",
          "title": "0@user",
          "updated": "2016-10-25 21:41:03",
          "content": {
            "id": "44c552d3-0868-4e36-9b9b-160bad558d89",
            "name": "UserSignedUp",
            "version": 0,
            "metadata": {},
            "createdAt": "2016-10-25 21:41:03",
            "payload": {}
          }
        },
        {
          "id": "http://site.com/streams/user/events/1",
          "title": "1@user",
          "updated": "2016-10-25 21:43:56",
          "content": {
            "id": "e902f95f-60d0-4bc5-afcf-13eddc6eed23",
            "name": "UserSignedUp",
            "version": 1,
            "metadata": {},
            "createdAt": "2016-10-25 21:43:56",
            "payload": {}
          }
        }
      ]
    }
    
### Stream Navigation

The default JSON representation of a stream allows you to navigate through the stream using hypermedia links.

If the `user` stream contained 42 events (versions 0 - 41), sending a GET request to `http://site.com/streams/user/0` would respond with the following `next` link:

    {
      "uri": "http://site.com/streams/user/42",
      "relation": "next"
    }
    
To get the next event(s) in this stream you would poll the `next` URI.

**If the stream has no events from the minimum version you specified there will be no `next` link present in the stream.**

## Getting a Stream Event

#### Request Placeholders

- `streamName`: The name of the stream.
- `version`: The version of the event.

<table>
  <tr>
    <th>Request</th>
    <th>Response</th>
    <th>Info</th>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/events/{version}</td>
    <td>415 Unsupported Media Type</td>
    <td>If the 'Accept' header in the request is not the same as the output content type of the formatter. The 'Content-Type' of the response will be the content type that is supported by the server.</td>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/events/{version}</td>
    <td>404 Not Found</td>
    <td>If the stream or an event with the specified version does not exist.</td>
  </tr>
  <tr>
    <td>GET /streams/{streamName}/events/{version}</td>
    <td>200 Ok</td>
    <td>The body of the response will contain the stream event.</td>
  </tr>
</table>

# Customization

## Formatters

A formatter takes a stream or an event and formats it into a string which is then sent back as the body of the response.

### Stream Formatter

This formatter is called when [getting a stream](#getting-a-stream).

1. Implement `Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\StreamFormatter`
2. Define your custom formatter as a service in the dependency injection container.
3. Set the stream formatter to the service ID in your `app/config/config.yml` file.

#### Methods to Implement
- `getOutputContentType()`: Returns a string specifying the content type of the data which the formatter returns.
- `format()`: Returns a string representation of a stream.

### Stream Event Formatter

This formatter is called when [getting a single stream event](#getting-a-stream-event).

1. Implement `Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\StreamEventFormatter`
2. Define your custom formatter as a service in the dependency injection container.
3. Set the stream event formatter to the service ID in your `app/config/config.yml` file.

#### Methods to Implement
- `getOutputContentType()`: Returns a string specifying the content type of the data which the formatter returns.
- `format()`: Returns a string representation of a stream event.
