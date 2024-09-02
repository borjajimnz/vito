<?php

namespace App\SiteTypes;

use App\Enums\SiteFeature;
use App\SSH\Git\Git;
use App\SSH\HasScripts;
use App\SSH\Services\Webserver\Webserver;
use Illuminate\Validation\Rule;

class NodeJs extends AbstractSiteType
{
    use HasScripts;

    public function language(): string
    {
        return 'reverse-proxy';
    }

    public function supportedFeatures(): array
    {
        return [
            SiteFeature::DEPLOYMENT,
            SiteFeature::ENV,
            SiteFeature::SSL,
            SiteFeature::QUEUES,
        ];
    }

    public function createRules(array $input): array
    {
        return [
            'port' => 'required|integer',
            'node_version' => [
                'required',
            ],
            'source_control' => [
                'required',
                Rule::exists('source_controls', 'id'),
            ],
        ];
    }

    public function createFields(array $input): array
    {
        return [
            'source_control_id' => $input['source_control'] ?? '',
            'repository' => $input['repository'] ?? '',
            'branch' => $input['branch'] ?? '',
            'node_version' => $input['node_version'] ?? '',
        ];
    }

    public function data(array $input): array
    {
        return [
            'url' => $this->site->getUrl(),
            'port' => $input['port'],
            'node_version' => $input['node_version'] ?? '',
        ];
    }

    public function install(): void
    {
        /** @var Webserver $webserver */
        $webserver = $this->site->server->webserver()->handler();
        $webserver->createVHost($this->site);
        $this->progress(15);
        if (data_get($this->site, 'repository')) {
            $this->deployKey();
            $this->progress(30);
            app(Git::class)->clone($this->site);
            $this->progress(65);
        }

        $this->site->server->ssh()->exec(
            $this->getScript('install-nvm.sh', [
                'node_version' => data_get($this->site, 'type_data.node_version')
            ]),
            'install-node-version'
        );
        $this->progress(75);
    }

    public function editRules(array $input): array
    {
        return [
        ];
    }

    public function edit(): void
    {
        //
    }
}
