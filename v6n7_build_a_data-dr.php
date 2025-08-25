<?php

class DataDrivenDevOpsPipelineIntegrator {
    private $pipelineConfig;
    private $dataStore;

    public function __construct($pipelineConfig, $dataStore) {
        $this->pipelineConfig = $pipelineConfig;
        $this->dataStore = $dataStore;
    }

    public function integratePipeline() {
        $stages = $this->pipelineConfig['stages'];

        foreach ($stages as $stage) {
            $stageType = $stage['type'];
            $stageConfig = $stage['config'];

            switch ($stageType) {
                case 'build':
                    $this->build($stageConfig);
                    break;
                case 'deploy':
                    $this->deploy($stageConfig);
                    break;
                case 'test':
                    $this->test($stageConfig);
                    break;
                default:
                    throw new Exception("Invalid stage type: $stageType");
            }
        }
    }

    private function build($config) {
        $builder = new BuildTool($config['tool']);
        $builder->setSourceCode($this->dataStore->getSourceCode());
        $builder->build();
    }

    private function deploy($config) {
        $deployer = new DeployTool($config['tool']);
        $deployer->setBuiltArtifact($this->dataStore->getBuiltArtifact());
        $deployer->deploy();
    }

    private function test($config) {
        $tester = new TestTool($config['tool']);
        $tester->setDeployedArtifact($this->dataStore->getDeployedArtifact());
        $tester->test();
    }
}

class BuildTool {
    private $tool;

    public function __construct($tool) {
        $this->tool = $tool;
    }

    public function setSourceCode($sourceCode) {
        // implement source code setup for build tool
    }

    public function build() {
        // implement build process using build tool
    }
}

class DeployTool {
    private $tool;

    public function __construct($tool) {
        $this->tool = $tool;
    }

    public function setBuiltArtifact($builtArtifact) {
        // implement built artifact setup for deploy tool
    }

    public function deploy() {
        // implement deploy process using deploy tool
    }
}

class TestTool {
    private $tool;

    public function __construct($tool) {
        $this->tool = $tool;
    }

    public function setDeployedArtifact($deployedArtifact) {
        // implement deployed artifact setup for test tool
    }

    public function test() {
        // implement test process using test tool
    }
}

class DataStore {
    private $sourceCode;
    private $builtArtifact;
    private $deployedArtifact;

    public function getSourceCode() {
        return $this->sourceCode;
    }

    public function getBuiltArtifact() {
        return $this->builtArtifact;
    }

    public function getDeployedArtifact() {
        return $this->deployedArtifact;
    }

    public function setSourceCode($sourceCode) {
        $this->sourceCode = $sourceCode;
    }

    public function setBuiltArtifact($builtArtifact) {
        $this->builtArtifact = $builtArtifact;
    }

    public function setDeployedArtifact($deployedArtifact) {
        $this->deployedArtifact = $deployedArtifact;
    }
}

// Example usage:
$pipelineConfig = array(
    'stages' => array(
        array(
            'type' => 'build',
            'config' => array(
                'tool' => 'maven'
            )
        ),
        array(
            'type' => 'deploy',
            'config' => array(
                'tool' => 'aws'
            )
        ),
        array(
            'type' => 'test',
            'config' => array(
                'tool' => 'junit'
            )
        )
    )
);

$dataStore = new DataStore();
$dataStore->setSourceCode('source code');

$integrator = new DataDrivenDevOpsPipelineIntegrator($pipelineConfig, $dataStore);
$integrator->integratePipeline();

?>