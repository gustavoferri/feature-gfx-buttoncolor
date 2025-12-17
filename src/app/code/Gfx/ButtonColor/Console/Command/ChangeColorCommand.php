<?php
declare(strict_types=1);

namespace Gfx\ButtonColor\Console\Command;

use Gfx\ButtonColor\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeColorCommand extends Command
{
    private const ARG_HEX = 'hex';
    private const ARG_STORE_ID = 'store_id';

    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository,
        private readonly WriterInterface $configWriter,
        private readonly TypeListInterface $cacheTypeList
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('color:change')
            ->setDescription('Change buttons background color for a given store-view (store scope).')
            ->addArgument(self::ARG_HEX, InputArgument::REQUIRED, 'HEX color without # (e.g. 000000)')
            ->addArgument(self::ARG_STORE_ID, InputArgument::REQUIRED, 'Store-view ID (integer)');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hex = (string) $input->getArgument(self::ARG_HEX);
        $storeIdRaw = (string) $input->getArgument(self::ARG_STORE_ID);

        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            $output->writeln('<error>Invalid HEX format. Use exactly 6 hex chars, e.g. 000000 or FF00AA.</error>');
            return Command::FAILURE;
        }

        if (!ctype_digit($storeIdRaw)) {
            $output->writeln('<error>Invalid store_id. It must be an integer.</error>');
            return Command::FAILURE;
        }

        $storeId = (int) $storeIdRaw;

        try {
            $store = $this->storeRepository->getById($storeId);
        } catch (NoSuchEntityException) {
            $output->writeln(sprintf(
                '<error>Store-view not found: ID %d. Please provide an existing store-view ID.</error>',
                $storeId
            ));
            return Command::FAILURE;
        }

        $normalized = '#' . strtoupper($hex);

        $this->configWriter->save(
            Config::XML_PATH_BUTTON_COLOR,
            $normalized,
            ScopeInterface::SCOPE_STORES,
            $storeId
        );

        $this->cacheTypeList->cleanType('config');
        $this->cacheTypeList->cleanType('full_page');

        $output->writeln(sprintf(
            '<info>OK! Store-view %d (%s) button color set to %s</info>',
            $storeId,
            (string) $store->getCode(),
            $normalized
        ));

        return Command::SUCCESS;
    }
}
