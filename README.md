# Kōjō Worker Decorator Component Fitness

Examples of how to use the [Kōjō Worker Decorator Component](https://github.com/neighborhoods/KojoWorkerDecoratorComponent)

## Examples

Examples include:
* [Success Worker](#success-worker)
* [Failing Worker](#failing-worker)
* [Transient Failing Worker](#transient-failing-worker)
* [Decorator Parameters](#decorator-parameters)
* [Custom Decorator](#custom-decorator)
* [Named Worker](#named-worker)

All examples use Buphalo templates as much as possible.

### Success Worker

This component has a worker logging a success message and completing successfully.  
This worker can be used as a start point for all implementations. It has the default decorator stack and defines only Symfony DI parameters without a default value.

After scheduling a job of this type, the job should complete successfully and be removed after a minute.

### Failing Worker

This component has a worker throwing a non-transient exception. It doesn't perform any other operations.  
It has the default decorator stack.

The `ExceptionHandlingDecorator` is supposed to catch the exception, log it and hold the job.

### Transient Failing Worker

This component has a worker throwing a transient exception. It doesn't perform any other operations.  
It has the default decorator stack.

The `ExceptionHandlingDecorator` is supposed to catch the exception, log it and retry the job.

### Decorator Parameters

This component has a worker logging a success message and completing successfully. 
It has the default decorator stack, but defines Symfony DI parameter values for all decorator related parameters.  
Pay attention to the order in which the source paths are added to the container builder in the `Proxy`.

After scheduling a job of this type, the job should complete successfully and be removed after a minute.  
Setting the value of a decorator to a value which is not allowed, e.g. `delaySeconds` of `CrashedThresholdDecorator` to `0`, would cause the container in the `Proxy` to fail building. Since the `Proxy` doesn't have a catch statement the job will panic. That proves that the overridden values are passed to the decorators.

### Custom Decorator

This component has a worker logging a success message and completing successfully.  
The component also has a custom decorator, which is added to the default decorator stack. The decorator logs a message before and after running the worker.

After scheduling a job of this type, the job should complete successfully and be removed after a minute.  
The standard output will show the two logged messages.

### Named Worker

Almost identical to [Success Worker](#success-worker), only difference is the worker name. The worker is named `CustomWorkerName`, not `Worker`.  
This example shows that Buphalo templates work with any worker name.

## Setup

To run the examples locally do the following steps. The examples will be run inside a docker container, since Kōjō requires a database and redis server.

Clone the GitHub repository.
``` bash
# using ssh (recommended)
$ git clone git@github.com:neighborhoods/KojoWorkerDecoratorComponentFitness.git
# or HTTP
$ git clone https://github.com/neighborhoods/KojoWorkerDecoratorComponentFitness.git
```
Step into the folder and install dependencies. Generate the prefabricated files and Buphalo templates.
``` bash
$ cd KojoWorkerDecoratorComponentFitness
$ composer install
$ vendor/bin/prefab
$ bin/buphalo
```
Launch the docker container.
``` bash
$ docker-compose up
```
After the container is ready, run migrations inside the `app` container.
``` bash
$ docker-compose exec app ./vendor/bin/phinx migrate
```

Schedule a worker you'd like to run, for example
``` bash
$ docker-compose exec app bin/schedule-success-worker-job.php 
```
Run Kōjō in the `app` container and observe the logs.
``` bash
$ docker-compose exec app bin/kojo
```
You can also observe the database records changing. Connect to the database inside the container by running
``` bash
$ docker-compose exec postgres psql --username=user -W kojo_worker_decorator_component_fitness
```
Enter the password `password` and run SQL to see the Kōjō job states.
``` postgresql
select * from kojo_job;
```
